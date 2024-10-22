@extends('admin.main-dashboard')

@section('adminSection')
    <section class="userList--sec m-3 rounded p-4">
        <div class="container">
            <h1 class="text-white fs-5 mb-4 text-uppercase position-relative">My Tasks</h1>

            <!-- Search bar -->
            <form method="GET" action="{{ route('tasks.assigned') }}">
                <div class="input-group mb-3 d-flex align-items-center gap-0">
                    <input type="text" name="search" class="form-control searchInput--myTask" placeholder="Search tasks..."
                        value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>

            <!-- Filters for task status -->
            <div class="btn-group mb-3" role="group">
                <a href="{{ route('tasks.assigned') }}"
                    class="btn btn-outline-primary {{ request('status') == null ? 'active' : '' }}">
                    All
                </a>
                <a href="{{ route('tasks.assigned', ['status' => 'Pending']) }}"
                    class="btn btn-outline-primary {{ request('status') == 'Pending' ? 'active' : '' }}">
                    Pending
                </a>
                <a href="{{ route('tasks.assigned', ['status' => 'In Progress']) }}"
                    class="btn btn-outline-primary {{ request('status') == 'In Progress' ? 'active' : '' }}">
                    In Progress
                </a>
                <a href="{{ route('tasks.assigned', ['status' => 'Completed']) }}"
                    class="btn btn-outline-primary {{ request('status') == 'Completed' ? 'active' : '' }}">
                    Completed
                </a>
            </div>

            <!-- Task list -->
            <div class="list-group d-flex align-items-center flex-wrap">
                @forelse($tasks as $task)
                    <a href="{{ route('tasks.show', $task->id) }}" class="list-group-item list-group-item-action">
                        <h5>{{ $task->name }}</h5>
                        <ul>
                            <li><span>Assigned On : {{ \Carbon\Carbon::parse($task->start_date)->format('d, M Y') }}</span>
                            </li>
                            <li><span>Target Date : {{ \Carbon\Carbon::parse($task->end_date)->format('d, M Y') }}</span>
                            </li>
                        </ul>
                        <small>{{ $task->priority }}</small>
                    </a>
                @empty
                    <p class="text-white">No tasks assigned to you yet.</p>
                @endforelse
            </div>

            <!-- Pagination links -->
            <div class="mt-3">
                {{ $tasks->links() }}
            </div>
        </div>
    </section>
@endsection

@push('js')
@php
    $userID = auth()->id();
@endphp

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userId = @json($userID); 
            console.log(userId);
            window.Echo.private('tasks.assigned.' + userId)
                .listen('TaskAssignedEvent', (task) => {
                    console.log(task);
                    const taskList = document.querySelector('.list-group');
                    const newTaskHtml = `<a href="/tasks/${task.id}" class="list-group-item list-group-item-action">
                                <h5>${task.name}</h5>
                                <li><span>Assigned On : ${task.start_date}</span></li>
                                <li><span>Target Date :  ${task.end_date}}}</span></li>
                                <small>${task.priority}</small>
                             </a>`;
                    taskList.insertAdjacentHTML('beforeend', newTaskHtml);
                });
        });
    </script>
@endpush
