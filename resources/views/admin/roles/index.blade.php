@extends('admin.main-dashboard')

@section('adminSection')
    <section class="roleList--sec m-3 rounded p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="text-white fs-5 m-0 text-uppercase position-relative">All Roles</h1>
            <a href="{{ route('roles.create') }}" class="btn">Add New Role</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">Role Name</th>
                    <th scope="col" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            <ul class="d-flex align-items-center gap-3 justify-content-end">
                                <li>
                                    <a href="{{ route('roles.edit', $role->employee_role_id) }}" class="btn btn-success">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('roles.destroy', $role->employee_role_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa-regular fa-trash-can fa-fw"></i>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection
