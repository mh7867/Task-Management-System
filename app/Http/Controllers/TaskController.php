<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Models\TaskEffort;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function create()
    {
        $users = User::with('employeeRole')->get();
        $filteredUsers = $users->filter(function ($user) {
            return !in_array($user->employeeRole->name, ['Project Manager']);
        });
        if (auth()->check() && auth()->user()->is_admin || auth()->user()->employeeRole->name === "Project Manager") {
            return view('admin.tasks.create', compact('filteredUsers'));
        } else {
            return redirect()->route('tasks.index');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|string',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,doc,mp4,webm,zip',
        ]);

        $task = new Task();
        $task->name = $validated['name'];
        $task->description = $validated['description'];
        $task->priority = $validated['priority'];
        $task->start_date = $validated['start_date'];
        $task->end_date = $validated['end_date'];
        $task->status = $validated['status'];
        $task->created_by = auth()->id();
        $task->save();


        if (isset($validated['assigned_to'])) {
            $task->assignedUsers()->sync($validated['assigned_to']);

            foreach ($validated['assigned_to'] as $userId) {
                $user = User::find($userId);
                Notification::send($user, new TaskAssigned($task, [$user], auth()->user()));
                event(new \App\Events\TaskAssignedEvent($task, $userId));
            }
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('task_files', 'public');
                $task->files()->create(['file_path' => $path]);
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function index()
    {
        $tasks = Task::with('assignedUsers')
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('admin.tasks.allTask', compact('tasks'));
    }

    public function show(Task $task)
    {
        $task->load('files', 'assignedUsers');
        return view('admin.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|string',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'exists:users,id',
        ]);

        $task->name = $validated['name'];
        $task->description = $validated['description'];
        $task->priority = $validated['priority'];
        $task->start_date = $validated['start_date'];
        $task->end_date = $validated['end_date'];
        $task->status = $validated['status'];
        $task->assigned_to = isset($validated['assigned_to']) ? json_encode($validated['assigned_to']) : $task->assigned_to;
        $task->save();

        if (isset($validated['assigned_to'])) {
            $task->assignedUsers()->sync($validated['assigned_to']);

            foreach ($validated['assigned_to'] as $userId) {
                $user = User::find($userId);
                Notification::send($user, new TaskAssigned($task, [$user], auth()->user()));
                event(new \App\Events\TaskAssignedEvent($task, $userId));
            }
        }

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    // Start Timer
    public function startTimer(Request $request, $taskID)
    {
        $user = Auth::user();
        $task = Task::findOrFail($taskID);

        $effort = TaskEffort::firstOrCreate(
            ['task_id' => $task->id, 'user_id' => $user->id],
            ['elapsed_time' => 0, 'is_running' => false]
        );

        if (!$effort->is_running) {
            $effort->start_time = now();
            $effort->is_running = true;
            $effort->save();
        }

        return response()->json([
            'status' => 'Timer started',
            'elapsedTime' => $effort->elapsed_time,
        ]);
    }

    // Stop Timer
    public function stopTimer(Request $request, $taskID)
    {
        $user = Auth::user();
        $task = Task::findOrFail($taskID);

        $effort = TaskEffort::where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($effort->is_running) {
            $timeDiff = Carbon::now()->diffInSeconds(Carbon::parse($effort->start_time));
            $effort->elapsed_time += $timeDiff;
            $effort->is_running = false;
            $effort->start_time = null;
            $effort->save();
        }

        return response()->json([
            'status' => 'Timer stopped',
            'elapsedTime' => $this->formatElapsedTime($effort->elapsed_time),
        ]);
    }

    private function formatElapsedTime($timeDiff)
    {
        $hours = floor($timeDiff / 3600);
        $minutes = floor(($timeDiff % 3600) / 60);
        $seconds = $timeDiff % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    public function getTaskEfforts($taskID)
    {
        $task = Task::with('efforts.user')->findOrFail($taskID);
        $currentUserId = Auth::id();

        $myEfforts = $task->efforts->where('user_id', $currentUserId);
        $otherEfforts = $task->efforts->where('user_id', '!=', $currentUserId);

        return response()->json([
            'task' => $task,
            'myEfforts' => $myEfforts,
            'otherEfforts' => $otherEfforts,
        ]);
    }

    public function assignedTasks(Request $request)
    {
        $query = Task::with('assignedUsers')
            ->whereHas('assignedUsers', function ($q) {
                $q->where('user_id', auth()->id());
            });

        if ($request->has('status') && in_array($request->status, ['Pending', 'In Progress', 'Completed'])) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(8);
        return view('admin.tasks.myTask', compact('tasks'));
    }
}
