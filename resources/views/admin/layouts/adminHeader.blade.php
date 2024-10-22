<header class="admin--header">
    <div class="row align-items-center">
        <div class="col-md-6">
            <div class="adminHeader--menu d-flex gap-1 flex-column">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="col-md-6">
            <ul class="admin--headerInfo d-flex align-items-center justify-content-end gap-4">
                <li><i class="fa-solid fa-magnifying-glass"></i></li>
                <li><i class="fa-regular fa-sun"></i></li>
                <li class="position-relative">
                    <a href="javascript:;" class="notificationBtn position-relative">
                        <i class="fa-regular fa-bell"></i>
                        <span id="notificationCount"
                            class="notification-count position-absolute top-0 end-0 d-none align-items-center justify-content-center rounded-circle">0</span>
                    </a>
                    <!-- Notifications Dropdown in the Header -->
                    <div id="notificationsBox" class="notifications-box position-absolute end-0 rounded">
                        @foreach (auth()->user()->notifications as $notification)
                            <div class="notification-item">
                                <strong>{{ $notification->data['title'] }}</strong>
                                <p>{{ $notification->data['message'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </li>
                <li><i class="fa-solid fa-expand"></i></li>
                <li>
                    <div class="userInfo--box d-flex align-items-center gap-2">
                        <div class="userImg position-relative">
                            <img src="{{ Auth::user()->avatar ? asset('/storage/' . Auth::user()->avatar) : asset('/storage/image/user-img-default.jfif') }}"
                                class="rounded-circle">
                            <span class="active"></span>
                        </div>
                        <div class="userDetails d-flex justify-content-center flex-column">
                            <h4 class="username">{{ Auth::user()->name }}</h4>
                            <span class="userPosition">
                                {{ Auth::user()->employeeRole ? Auth::user()->employeeRole->name : 'No Role Assigned' }}
                            </span>
                        </div>
                    </div>
                </li>
                <li class="position-relative">
                    <a href="javascript:;" class="settingBtn"><i class="fa-solid fa-gear"></i></a>

                    {{-- Settings -- Box -- Here --}}

                    <div class="settingBox--main position-absolute end-0 rounded">
                        <ul class="d-flex align-items-center flex-column">
                            <li class="setting-items w-100">
                                <a href="@if (Auth::user()->is_admin) {{ route('admin.users.edit', ['user' => Auth::id()]) }}
                                        @else
                                            {{ route('users.edit', ['user' => Auth::id()]) }} @endif"
                                    class="p-3 text-white d-block">
                                    <i class="fa-solid fa-user-pen text-white"></i> Edit Profile
                                </a>
                            </li>
                            <li class="setting-items w-100"><a href="{{ route('tasks.assigned') }}" class="p-3 text-white d-block"><i
                                        class="fa-solid fa-thumbtack"></i> My Task</a></li>
                            <li class="setting-items w-100"><a href="" class="p-3 text-white d-block"><i
                                        class="fa-solid fa-gear"></i> Settings</a></li>
                            <li class="setting-items w-100">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <a href="{{ route('logout') }}" class="p-3 text-white d-block"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                                    </a>
                                </form>
                            </li>

                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>



@push('js')
    @php
        $userID = auth()->id(); 
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userId = @json($userID); 

            window.Echo.private(`notifications.${userId}`)
                .listen('.task-assigned', (event) => {
                    console.log('Notification received:', event);
                    displayNotification(event); 
                });

            function displayNotification(event) {
                let notificationCountElem = document.getElementById('notificationCount');
                let notificationCount = parseInt(notificationCountElem.textContent);

                if (notificationCount === 0) {
                    notificationCountElem.textContent = notificationCount + 1;
                    notificationCountElem.classList.remove('d-none');
                    notificationCountElem.classList.add('d-flex');
                } else {
                    notificationCountElem.textContent = notificationCount + 1;
                }

                const notificationsBox = document.getElementById('notificationsBox');
                const newNotification = document.createElement('div');
                newNotification.classList.add('notification-item');
                newNotification.innerHTML = `
                    <strong>${event.title}</strong>
                    <p>${event.message}</p>
                    <small>Assigned by: ${event.assigned_by}</small>
                `;
                notificationsBox.prepend(newNotification);
            }
        });
    </script>
@endpush

