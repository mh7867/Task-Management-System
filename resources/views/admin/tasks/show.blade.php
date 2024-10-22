@extends('admin.main-dashboard')
@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('adminSection')
    <div class="row">
        <div class="col-md-9">
            <!-- Task Details -->
            @php
                $id = auth()->id();
                $user = auth()->user();
                $userRoles = $user && $user->employeeRoles ? $user->employeeRoles->pluck('name')->toArray() : [];
                $isAssignedUser = $task->assignedUsers->contains('id', $id);
                $isAdmin = auth()->user()->is_admin;
                $assignedProjectManager = $task->createdByUser->id;
                $isAssignedProjectManager = auth()->id() === $assignedProjectManager;
            @endphp

            <section class="userList--sec border-0 m-3 rounded">
                <div class="taskDetail--header d-flex align-items-center justify-content-between p-3">
                    <h1 class="text-white fs-5 m-0 text-uppercase position-relative">Task Summary</h1>
                    @if ($isAdmin || $isAssignedProjectManager)
                        <a href="" class="btn btn-primary">Edit Tasks</a>
                    @endif
                </div>
                <div class="taskDetail p-4 text-white">
                    <h2 class="text-white fs-4 mb-3 task-title px-3 position-relative">{{ $task->name }}</h2>
                    <p class="text-white">{!! $task->description !!}</p>
                </div>
                <div class="taskDeatil--footer text-white d-flex align-items-center p-3 gap-5 justify-content-end">
                    <div class="assignedBy--box d-flex flex-column col">
                        <span class="text-muted">Assigned By</span>
                        <div class="assignedBy--name d-flex align-items-center gap-2">
                            <img src="{{ $task->createdByUser && $task->createdByUser->avatar ? Storage::url($task->createdByUser->avatar) : '/storage/image/user-img-default.jfif' }}"
                                alt="Avatar" style="width: 30px; height: 30px; border-radius: 50%;">
                            <span>{{ $task->createdByUser ? $task->createdByUser->name : 'Unknown' }}</span>
                        </div>
                    </div>
                    <div class="projectStart--date d-flex flex-column">
                        <span class="text-muted">Start Date</span>
                        {{ \Carbon\Carbon::parse($task->start_date)->format('d, F Y') }}
                    </div>
                    <div class="projectEnd--date d-flex flex-column">
                        <span class="text-muted">End Date</span>
                        {{ \Carbon\Carbon::parse($task->end_date)->format('d, F Y') }}
                    </div>
                    @if ($isAssignedUser)
                        <div class="projectEfforts d-flex flex-column">
                            <span class="text-muted">Efforts</span>
                            <form id="timerForm">
                                <input type="hidden" name="timerValue" id="timerValue" value="">
                            </form>
                            <div class="d-flex align-items-center">
                                <span id="timerDisplay" class="fs-5">
                                    <div id="myEffortContainer"></div>
                                </span>
                                <button id="startTimerBtn" class="btn btn-success ms-3">Start</button>
                                <button id="stopTimerBtn" class="btn btn-danger ms-3" disabled>Stop</button>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <!-- Task Chat -->
            @if ($isAssignedUser || $isAdmin || $isAssignedProjectManager)
                <section class="userList--sec border-0 m-3 rounded">
                    <div class="taskDetail--header d-flex align-items-center justify-content-between p-3">
                        <h1 class="text-white fs-5 m-0 text-uppercase position-relative">Task Discussion</h1>
                    </div>
                    <div id="chat-box" class="px-3">
                        @foreach ($task->discussions as $discussion)
                            <div
                                class="message d-flex mb-3 align-items-center {{ $discussion->user->id == auth()->id() ? 'justify-content-end' : '' }}">
                                @if ($discussion->user->id == auth()->id())
                                    <div class="messageInner flex-column d-flex align-items-end">
                                        <div class="messageHeader mb-1 d-flex align-items-center justify-content-between">
                                            <small class="text-muted">{{ $discussion->created_at->format('h:i A') }}</small>
                                            <ul class="d-flex flex-row-reverse align-items-center gap-2">
                                                <li>
                                                    <img src="{{ $discussion->user->avatar ? Storage::url($discussion->user->avatar) : '/storage/image/user-img-default.jfif' }}"
                                                        alt="{{ $discussion->user->name }}"
                                                        style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;">
                                                </li>
                                                <li>
                                                    <span class="text-white">{{ $discussion->user->name }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="messageBox text-white">
                                            {{ $discussion->message }}
                                        </div>
                                    </div>
                                @else
                                    <div class="messageInner d-flex flex-column">
                                        <div
                                            class="messageHeader mb-1 d-flex align-items-center justify-content-between gap-2">
                                            <ul class="d-flex align-items-center gap-2">
                                                <li>
                                                    <img src="{{ $discussion->user->avatar ? Storage::url($discussion->user->avatar) : '/storage/image/user-img-default.jfif' }}"
                                                        alt="{{ $discussion->user->name }}"
                                                        style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;">
                                                </li>
                                                <li>
                                                    <span class="text-white">{{ $discussion->user->name }}</span>
                                                </li>
                                            </ul>
                                            <small
                                                class="text-muted">{{ $discussion->created_at->format('h:i A') }}</small>
                                        </div>
                                        <div class="messageBox text-white">
                                            {{ $discussion->message }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="chatFooter">
                        <form id="chat-form" data-task-id="{{ $task->id }}" class="d-flex align-items-center p-4">
                            @csrf
                            <input name="message" id="message" class="form-control" placeholder="Type a message..."
                                autocomplete="off" />
                            <button type="button" id="emoji-button" class="btn btn-light me-2">😊</button>
                            <button type="submit" class="btn btn-primary h-100">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                    style="display: none;"></span>
                                Send
                            </button>
                        </form>
                        <div id="emojiWrapper"></div>
                    </div>
                </section>
            @endif

        </div>
        <div class="col-md-3 p-0">
            <!-- Additional Details -->
            <section class="userList--sec me-4 mt-3 rounded border-0">
                <div class="additionalBox--header p-3">
                    <h6 class="text-white position-relative">Additional Details</h6>
                </div>
                <ul class="additional-details">
                    <li class="text-white d-flex align-items-center gap-2 text-white justify-content-between flex-wrap">Task
                        ID : <span>{{ $task->id }}</span></li>
                    <li class="text-white d-flex align-items-center gap-2 justify-content-between flex-wrap">
                        Project Priority:
                        <span class="projectPriority--text" data-priority="{{ $task->priority }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </li>
                    <li class="text-white d-flex align-items-center gap-2 text-white justify-content-between flex-wrap">
                        Project Status:
                        <span
                            class="
                            @if ($task->status === 'Pending') text-warning
                            @elseif($task->status === 'in Progress') text-primary
                            @elseif($task->status === 'Completed') text-success @endif
                        ">
                            {{ ucfirst($task->status) }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center gap-2 text-white justify-content-between flex-wrap">Assigned To :
                        <ul class="assignedUsers d-flex gap-0 align-items-center px-2">
                            @foreach ($task->assignedUsers as $assignedUser)
                                <li><img src='/storage/{{ $assignedUser->avatar }}' alt="{{ $assignedUser->name }}"
                                        class="rounded-circle"></li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </section>

            <!-- Attachments -->
            <section class="userList--sec me-4 mt-3 rounded border-0">
                <div class="additionalBox--header p-3">
                    <h6 class="text-white position-relative">Attachments</h6>
                </div>
                <ul class="additional-details">
                    @if ($task->files->isEmpty())
                        <p class="p-4 text-white">No files available for this task.</p>
                    @else
                        @foreach ($task->files as $file)
                            <li
                                class="text-white d-flex align-items-center gap-2 text-white justify-content-between flex-wrap">
                                <div class="file-type">
                                    @php
                                        $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                    @endphp
                                    @if ($extension === 'zip')
                                        <img src="{{ asset('storage/image/zip.png') }}" alt="Zip File Icon" />
                                    @else
                                        <img src="{{ asset('storage/image/folder.png') }}" alt="Folder Icon" />
                                    @endif
                                </div>
                                <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                    class="filename text-white">{{ basename($file->file_path) }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </section>

            <!-- Efforts -->
            <section class="userList--sec me-4 mt-3 rounded border-0">
                <div class="additionalBox--header p-3">
                    <h6 class="text-white position-relative">Efforts</h6>
                </div>
                <div id="otherEffortContainer" class="p-2 text-white"></div>
            </section>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentUserId = @json($id);
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;

            const taskID = document.getElementById('chat-form').getAttribute('data-task-id');
            console.log('Task ID:', taskID);

            let emojiList = [];
            let categoriesList = [];

            // Fetch emojis
            fetch('https://emoji-api.com/emojis?access_key=d2617174a5e6de79fb0db0fcea823a7be6989f92')
                .then(response => response.json())
                .then(data => {
                    emojiList = data;
                })
                .catch(error => console.error('Error fetching emojis:', error));

            // Fetch categories
            fetch('https://emoji-api.com/categories?access_key=d2617174a5e6de79fb0db0fcea823a7be6989f92')
                .then(response => response.json())
                .then(data => {
                    categoriesList = data.map(category => category.slug);
                    renderCategoryTabs();
                })
                .catch(error => console.error('Error fetching categories:', error));

            const emojiButton = document.getElementById('emoji-button');
            const messageInput = document.getElementById('message');
            const emojiWrapper = document.getElementById('emojiWrapper');

            const emojiContainer = document.createElement('div');
            emojiContainer.classList.add('emoji-container');
            emojiContainer.style.display = 'none';
            emojiContainer.style.backgroundColor = '#1a1c1e';
            emojiContainer.style.border = '1px solid #ddd';
            emojiContainer.style.padding = '10px';
            emojiContainer.style.overflowY = 'auto';
            emojiContainer.style.maxHeight = '250px';
            emojiContainer.style.zIndex = '1000';
            emojiContainer.style.width = '100%';
            emojiWrapper.appendChild(emojiContainer);

            // Create search input
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Search emojis...';
            searchInput.style.width = '100%';
            searchInput.style.marginBottom = '10px';
            emojiContainer.appendChild(searchInput);

            // Create categories tab container
            const categoryTabsContainer = document.createElement('div');
            categoryTabsContainer.style.display = 'flex';
            categoryTabsContainer.style.flexWrap = 'wrap';
            categoryTabsContainer.style.marginBottom = '10px';
            emojiContainer.appendChild(categoryTabsContainer);

            // Render category tabs
            const renderCategoryTabs = () => {
                const allTab = document.createElement('button');
                allTab.textContent = 'All';
                allTab.style.margin = '5px';
                allTab.style.padding = '5px 10px';
                allTab.style.border = 'none';
                allTab.style.cursor = 'pointer';
                allTab.style.background = '#333';
                allTab.style.color = '#fff';
                allTab.addEventListener('click', () => filterEmojis(''));
                categoryTabsContainer.appendChild(allTab);

                // Add other category tabs
                categoriesList.forEach(category => {
                    const tab = document.createElement('button');
                    tab.textContent = category;
                    tab.style.margin = '5px';
                    tab.style.padding = '5px 10px';
                    tab.style.border = 'none';
                    tab.style.cursor = 'pointer';
                    tab.style.background = '#333';
                    tab.style.color = '#fff';
                    tab.addEventListener('click', () => filterEmojis(category));
                    categoryTabsContainer.appendChild(tab);
                });
            };

            // Render emojis
            const renderEmojis = (emojis) => {
                const emojiContent = document.createElement('div');
                emojiContent.style.display = 'grid';
                emojiContent.style.gridTemplateColumns = 'repeat(auto-fill, minmax(25px, 1fr))';
                emojiContent.style.gap = '5px';

                for (const emoji of emojis) {
                    const emojiSpan = document.createElement('span');
                    emojiSpan.textContent = emoji.character;
                    emojiSpan.style.fontSize = '25px';
                    emojiSpan.style.cursor = 'pointer';
                    emojiSpan.style.margin = '5px';
                    emojiSpan.addEventListener('click', function() {
                        messageInput.value += emoji.character + ' ';
                        emojiContainer.style.display = 'none';
                    });
                    emojiContent.appendChild(emojiSpan);
                }

                if (emojiContainer.children.length > 2) {
                    emojiContainer.removeChild(emojiContainer.lastChild);
                }
                emojiContainer.appendChild(emojiContent);
            };

            const filterEmojis = (selectedCategory) => {
                const searchTerm = searchInput.value.toLowerCase();

                const filteredEmojis = emojiList.filter(emoji => {
                    const matchesSearch = emoji.unicodeName.toLowerCase().includes(searchTerm);
                    const matchesCategory = !selectedCategory || emoji.group.toLowerCase() ===
                        selectedCategory;
                    return matchesSearch && matchesCategory;
                });

                renderEmojis(filteredEmojis);
            };

            searchInput.addEventListener('input', () => filterEmojis(''));

            emojiButton.addEventListener('click', function(e) {
                if (emojiContainer.style.display === 'none') {
                    renderEmojis(emojiList);
                    emojiContainer.style.display = 'block';
                } else {
                    emojiContainer.style.display = 'none';
                }
            });

            document.addEventListener('click', function(e) {
                if (!emojiButton.contains(e.target) && !emojiContainer.contains(e.target)) {
                    emojiContainer.style.display = 'none';
                }
            });

            window.Echo.connector.pusher.connection.bind('connected', function() {
                const socketId = window.Echo.socketId();
                console.log('Socket ID:', socketId);

                if (taskID) {
                    window.Echo.channel(`task.${taskID}`)
                        .listen('TaskMessagePosted', (e) => {
                            console.log('New message received:', e);

                            const userName = e.user.name;
                            const baseURL = window.location.origin;
                            const userAvatar = `${baseURL}/storage/${e.user.avatar}`;
                            const message = e.message;
                            const createdAt = e.created_at;

                            const messageDiv = document.createElement('div');
                            messageDiv.classList.add('message', 'd-flex', 'mb-3', 'align-items-center');
                            if (e.user.id == currentUserId) {
                                messageDiv.classList.add('justify-content-end');
                                messageDiv.innerHTML = `<div class="messageInner flex-column d-flex align-items-end">
                            <div class="messageHeader mb-1 d-flex align-items-center justify-content-between">
                                 <small class="text-muted">${createdAt}</small>
                                <ul class="d-flex flex-row-reverse align-items-center gap-2">
                                    <li><img src="${userAvatar}" alt="${userName}" style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;"></li>
                                    <li><span class="text-white">${userName}</span>
                                </ul>
                            </div>
                            <div class="messageBox text-white">
                                 ${message} 
                            </div>
                        </div>`;
                            } else {
                                messageDiv.innerHTML = `<div class="messageInner d-flex flex-column">
                            <div class="messageHeader mb-1 d-flex align-items-center justify-content-between gap-2">
                                <ul class="d-flex align-items-center gap-2">
                                    <li><img src="${userAvatar}" alt="${userName}" style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px;"></li>
                                    <li><span class="text-white">${userName}</span>
                                </ul>
                                <small class="text-muted">${createdAt}</small>
                            </div>
                            <div class="messageBox text-white">
                                 ${message} 
                            </div>
                        </div>`;
                            }

                            chatBox.appendChild(messageDiv);
                            chatBox.scrollTop = chatBox.scrollHeight;


                            const submitButton = document.querySelector(
                                '#chat-form button[type="submit"]');
                            submitButton.disabled = false;
                            submitButton.innerHTML = 'Send';
                        });
                } else {
                    console.error('Task ID is not defined!');
                }

                document.getElementById('chat-form').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const form = this;
                    const message = form.message.value;
                    const taskId = form.getAttribute('data-task-id');
                    const submitButton = form.querySelector('button[type="submit"]');

                    submitButton.disabled = true;
                    submitButton.innerHTML = 'Sending...';

                    fetch(`{{ route('tasks.discussions.store', $task->id) }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'X-Socket-ID': socketId,
                            },
                            body: JSON.stringify({
                                message: message,
                                task: taskId
                            })
                        })
                        .then(response => response.json())
                        .catch(error => {
                            console.error('Error sending message:', error);
                            submitButton.disabled = false;
                            submitButton.innerHTML = 'Send';
                        });

                    form.reset();
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            const taskID = $('#chat-form').data('task-id');
            const myEffortContainer = $('#myEffortContainer');
            const otherEffortContainer = $('#otherEffortContainer');
            const timerDisplay = $('#timerDisplay');

            let timerInterval = null;
            let startTime = null;
            let elapsedTime = 0;

            function fetchTaskEfforts() {
                $.ajax({
                    url: `/tasks/${taskID}/efforts`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        console.log('Efforts data:', data);
                        if (data.myEfforts && data.otherEfforts) {
                            displayEfforts(data.myEfforts, myEffortContainer, true);
                            displayEfforts(data.otherEfforts, otherEffortContainer, false);
                        } else {
                            alert('Failed to fetch task efforts.');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching task efforts:', error);
                    }
                });
            }

            function displayEfforts(efforts, container, isCurrentUser) {
                container.empty();
                if (efforts.length === 0) {
                    container.append('<p>No efforts recorded.</p>');
                } else {
                    efforts.forEach(effort => {
                        const user = effort.user;
                        const elapsedTime = formatTimeDisplay(effort.elapsed_time);
                        const effortItem =
                            `<div class="effort-item"><p><strong>${user && !isCurrentUser ? user.name : ''}</strong> ${elapsedTime}</p></div>`;
                        container.append(effortItem);
                    });
                }
            }

            function formatTimeDisplay(timeDiff) {
                timeDiff = Math.max(0, timeDiff);
                const hours = Math.floor(timeDiff / 3600);
                const minutes = Math.floor((timeDiff % 3600) / 60);
                const seconds = timeDiff % 60;
                return `${formatTime(hours)}:${formatTime(minutes)}:${formatTime(seconds)}`;
            }

            function formatTime(time) {
                return time < 10 ? `0${time}` : time;
            }

            fetchTaskEfforts();

            const startTimerBtn = $('#startTimerBtn');
            const stopTimerBtn = $('#stopTimerBtn');

            startTimerBtn.on('click', function() {
                $.ajax({
                    url: `/tasks/${taskID}/start-timer`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.status === 'Timer started') {
                            startTime = new Date();
                            elapsedTime = data.elapsedTime || 0;
                            startTimer();
                            startTimerBtn.prop('disabled', true);
                            stopTimerBtn.prop('disabled', false);
                        } else {
                            alert('Failed to start the timer.');
                        }
                    },
                    error: function(error) {
                        console.error('Error starting timer:', error);
                    }
                });
            });

            stopTimerBtn.on('click', function() {
                clearInterval(timerInterval);
                elapsedTime += Math.floor((new Date() - startTime) / 1000);
                $.ajax({
                    url: `/tasks/${taskID}/stop-timer`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.status === 'Timer stopped') {
                            startTimerBtn.prop('disabled', false);
                            stopTimerBtn.prop('disabled', true);
                        } else {
                            alert('Failed to stop the timer.');
                        }
                    },
                    error: function(error) {
                        console.error('Error stopping timer:', error);
                    }
                });
            });

            function startTimer() {
                timerInterval = setInterval(function() {
                    const now = new Date();
                    const timeDiff = elapsedTime + Math.floor((now - startTime) / 1000);
                    timerDisplay.text(formatTimeDisplay(timeDiff));
                }, 1000);
            }
        });
    </script>
@endpush
