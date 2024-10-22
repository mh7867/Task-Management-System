@extends('admin.main-dashboard')
@section('adminSection')

    <div class="row">
        <div class="col-md-9">

            {{-- Task -- List -- Section -- Here --}}
            <section class="userList--sec border-0 m-3 rounded">

                {{-- Task -- Header -- Here --}}

                <div class="taskDetail--header d-flex align-items-center justify-content-between p-3">
                    <h1 class="text-white fs-5 m-0 text-uppercase position-relative">Total Tasks</h1>
                    @php
                        $user = auth()->user();
                        $employeeRoleName = $user->employeeRole ? $user->employeeRole->name : null;
                    @endphp
                    @if (auth()->check() && (auth()->user()->is_admin || $employeeRoleName === "Project Manager"))
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Task
                        </a>
                    @endif
                </div>

                @if (session('success'))
                    <div class="alert alert-success m-3">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- All -- Task -- Body -- Here --}}
                @if ($tasks->isEmpty())
                    <div class="text-white text-center p-3">No Task Availiable</div>
                @else
                    <div class="p-4 table-responsive">
                        <table class="totalTask--table text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Priority</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr class="text-center">
                                        <td>{{ $task->id }}</td>
                                        <td><a href="{{ route('tasks.show', $task->id) }}"
                                                class="text-white">{{ $task->name }}</a></td>
                                        <td><span class="projectPriority--text"
                                                data-priority="{{ $task->priority }}">{{ $task->priority }}</span></td>
                                        <td>{{ $task->start_date }}</td>
                                        <td>{{ $task->end_date }}</td>
                                        <td
                                            class="@if ($task->status === 'Pending') text-warning
                                        @elseif($task->status === 'in Progress') text-primary
                                        @elseif($task->status === 'Completed') text-success @endif">
                                            {{ $task->status }}</td>
                                        <td>
                                            <ul class="d-flex align-items-center">
                                                @foreach ($task->assignedUsers as $assignedUser)
                                                    <li><img src='/storage/{{ $assignedUser->avatar }}'
                                                            alt="{{ $assignedUser->name }}" class="rounded-circle"></li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <ul class="d-flex align-items-center gap-3">
                                                <li>
                                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this Task?');"
                                                        class="deleteForm">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fa-regular fa-trash-can fa-fw"></i>
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-success">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                <div>
                    {{-- Add Pagination Links --}}
                    @if ($tasks->hasPages())
                        <div class="pagination-wrapper d-flex justify-content-end p-3">
                            {{ $tasks->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </section>
            {{-- Task -- Chart -- Section -- Here --}}
            <section class="userList--sec border-0 m-3 rounded">
                @php
                    use Carbon\Carbon;

                    $tasksData = [];
                    $currentMonth = Carbon::now();

                    for ($i = 0; $i < 6; $i++) {
                        $monthName = $currentMonth->format('F');

                        // Clone the current month to prevent modifying the original Carbon instance
                        $startOfMonth = $currentMonth->copy()->startOfMonth()->toDateString();
                        $endOfMonth = $currentMonth->copy()->endOfMonth()->toDateString();

                        $pendingTasks = \App\Models\Task::where('status', 'Pending')
                            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                            ->count();
                        $inProgressTasks = \App\Models\Task::where('status', 'in Progress')
                            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                            ->count();
                        $completedTasks = \App\Models\Task::where('status', 'Completed')
                            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                            ->count();

                        $tasksData[] = [
                            'month' => $monthName,
                            'pending' => $pendingTasks,
                            'inProgress' => $inProgressTasks,
                            'completed' => $completedTasks,
                        ];

                        // Move to the previous month
                        $currentMonth->subMonth();
                    }
                @endphp

                {{-- TaskChart -- Header -- Here --}}

                <div class="taskDetail--header d-flex align-items-center justify-content-between p-3">
                    <h1 class="text-white fs-5 m-0 text-uppercase position-relative">Tasks Chart</h1>
                </div>
                <div>
                    <canvas id="myChart" width="400" height="200"></canvas>
                </div>
        </div>




        <div class="col-md-3">
            @php
                $pendingTasks = \App\Models\Task::where('status', 'Pending')->count();
                $inProgressTasks = \App\Models\Task::where('status', 'in Progress')->count();
                $completedTasks = \App\Models\Task::where('status', 'Completed')->count();
            @endphp
            <div class="all--taskDetails--main m-3 rounded">
                <div class="all--taskDetail--box p-4">
                    <div class="taskDetail--iconMain d-flex gap-4">
                        <div class="taskDetail--icon p-1 rounded d-flex align-items-center">
                            <img src="{{ asset('/storage/image/complete_task.svg') }}">
                        </div>
                        <div class="taskDetail--content">
                            <span class="text-white">Completed Tasks</span>
                            <h2 class="count text-white">{{ $completedTasks }}</h2>
                        </div>
                    </div>

                </div>
                <div class="all--taskDetail--box p-4">
                    <div class="taskDetail--iconMain d-flex gap-4">
                        <div class="taskDetail--icon p-1 rounded d-flex align-items-center">
                            <img src="{{ asset('/storage/image/inprogress_task.svg') }}">
                        </div>
                        <div class="taskDetail--content">
                            <span class="text-white">inProgress Tasks</span>
                            <h2 class="count text-white">{{ $inProgressTasks }}</h2>
                        </div>
                    </div>

                </div>
                <div class="all--taskDetail--box p-4">
                    <div class="taskDetail--iconMain d-flex gap-4">
                        <div class="taskDetail--icon p-1 rounded d-flex align-items-center">
                            <img src="{{ asset('/storage/image/pending_task.svg') }}">
                        </div>
                        <div class="taskDetail--content">
                            <span class="text-white">Pending Tasks</span>
                            <h2 class="count text-white">{{ $pendingTasks }}</h2>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $('.count').each(function() {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 1000,
                easing: 'swing',
                step: function(now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });
    </script>
    <script>
        const tasksData = @json($tasksData);

        const labels = tasksData.map(data => data.month).reverse();
        const pendingTasks = tasksData.map(data => data.pending).reverse();
        const inProgressTasks = tasksData.map(data => data.inProgress).reverse();
        const completedTasks = tasksData.map(data => data.completed).reverse();

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Pending Tasks',
                        data: pendingTasks,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'In Progress Tasks',
                        data: inProgressTasks,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Completed Tasks',
                        data: completedTasks,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    x: {
                        grid: {
                            color: '#ffffff1a',
                            borderColor: '#ffffff1a'
                        },
                        ticks: {
                            color: '#ffffffb3'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: 240,
                        grid: {
                            color: '#ffffff1a',
                            borderColor: '#ffffff1a'
                        },
                        ticks: {
                            color: '#ffffffb3'
                        }
                    }
                },
            }
        });
    </script>
@endpush
