<section class="sideBar--admin" style="background-color: #1a1c1e;">
    <div class="sideBar--main">
        {{-- Site -- Identity -- Here --}}
        <div class="sideBar--header d-flex align-items-center justify-content-center border-left-0">
            <div class="siteLogo--box w-50">
                <img src="{{ asset('storage/image/desktop-dark.png') }}" alt="Site Logo">
            </div>
            <div class="siteIcon--box" style="display: none;">
                <img src="{{ asset('storage/image/toggle-dark.png') }}" alt="Site Icon">
            </div>
        </div>
        <div class="sideBar--nav">
            <ul class="menu-navigation px-2 py-4">
                <li class="menuCategory"><span class="text-uppercase">main</span></li>
                <li class="menu-items d-flex gap-2 align-items-center {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i>
                    <a href="@if(Auth::user()->is_admin) {{ route('admin.dashboard') }} @else {{ route('user.dashboard') }}  @endif">Dashboard</a>
                </li>
                <li class="menuCategory"><span class="text-uppercase">general</span></li>
                @if (Auth::user()->is_admin)
                <li class="menu-items d-flex gap-2 align-items-center">
                    <i class="fa-solid fa-file"></i>
                    <a href="#">Pages</a>
                </li>
                <li class="menu-items d-flex gap-2 align-items-center {{ Request::is('admin/roles') ? 'active' : '' }}"><i class="fa-solid fa-user-tag"></i> <a href="{{ route('roles.index') }}">Roles</a></li>
                <li class="menu-items d-flex gap-2 align-items-center {{ Request::is('admin/users') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i>
                    <a href="{{ route('admin.users.index') }}">Users</a>
                </li>
                @endif
                <li class="menu-items d-flex gap-2 align-items-center {{ Request::is('tasks') ? 'active' : '' }}">
                    <i class="fa-solid fa-list-check"></i>
                    <a href="{{ route('tasks.index') }}">Task</a>
                </li>
                @if (Auth::user()->is_admin)
                <li class="menu-items d-flex gap-2 align-items-center">
                    <i class="fa-solid fa-gear"></i>
                    <a href="#">Settings</a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</section>
