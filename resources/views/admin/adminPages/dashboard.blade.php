@extends('admin.main-dashboard')

@section('adminSection')
    @if (session('success'))
        <div class="alert alert-success m-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mt-4 mx-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Welcome, {{ Auth::user()->name }}!</h4>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <a href="{{ route('logout') }}" class="btn btn-danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
            </form>
        </div>
        <div class="card-body">
            <p>You are logged in as an
                @if (Auth::user()->is_admin)
                    admin
                @else
                    employee
                @endif
            </p>
        </div>
    </div>

    <section class="activeUsers-sec userList--sec m-3 rounded p-4 overflow-hidden">
        <h1 class="text-white fs-5 m-0 text-uppercase position-relative">All Users</h1>
        <div class="activeUsers--boxMain d-flex align-items-center gap-3">
            @foreach ($users as $user)
                @if ($user->id !== Auth::id())
                    <div class="activeUser--box position-relative" data-user-id="{{ $user->id }}">
                        <img src="{{ asset('/storage/' . $user->avatar) }}" class="rounded-circle">
                        <h4 class="position-absolute start-50 translate-middle-x activeUser--name">
                            {{ $user->name }}</h4>
                        <span class="active {{ $user->active ? 'd-inline' : 'd-none' }}"></span>
                    </div>
                @endif
            @endforeach
        </div>
    </section>
@endsection


