<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Models\Task;
    use App\Models\TaskDiscussion;
    use App\Events\TaskMessagePosted;

    class TaskDiscussionController extends Controller
    {
        public function store(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $user = auth()->user();
        $message = $request->input('message');

        $discussion = $task->discussions()->create([
            'user_id' => $user->id,
            'message' => $message,
        ]);

        // Dispatch the event
        event(new TaskMessagePosted($user, $message, $task));

        return response()->json([
            'success' => true,
            'user' => [
                'name' => $user->name,
                'avatar' => $user->avatar,
                'message' => $discussion->message,
            ],
            'date' => now()->format('h:i A'), 
        ]);        
    }

    }
