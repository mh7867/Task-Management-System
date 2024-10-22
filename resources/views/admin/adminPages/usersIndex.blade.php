@extends('admin.main-dashboard')

@section('adminSection')
    <section class="userList--sec m-3 rounded p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="text-white fs-5 m-0 text-uppercase position-relative">All Users</h1>
            <a href="{{ route('admin.users.create') }}" class="btn">Add New</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Is Admin</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                @foreach ($users as $user)
                    <tr data-user-id="{{ $user->id }}" data-user-active="{{ $user->active }}">
                        <td>
                            <ul class="d-flex align-items-center gap-3 border-0">
                                <li class="position-relative">
                                    <img src="{{ $user->avatar ? asset('/storage/' . $user->avatar) : asset('/storage/image/user-img-default.jfif') }}" class="rounded-circle">
                                    <span class="active {{ $user->active ? 'd-inline' : 'd-none' }}"></span>
                                </li>
                                <li>{{ $user->name }}</li>
                            </ul>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->employeeRole->name ?? 'No Role' }}</td>
                        <td class="{{ $user->is_admin ? 'is_admin' : 'not_admin' }}"><span>{{ $user->is_admin ? 'Yes' : 'No' }}</span></td>
                        <td>
                            <ul class="d-flex align-items-center gap-3">
                                @can('delete', $user)
                                    <li>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" class="deleteForm">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa-regular fa-trash-can fa-fw"></i>
                                            </button>
                                        </form>
                                    </li>
                                @endcan
                                @can('update', $user)
                                    <li>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-success">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection
