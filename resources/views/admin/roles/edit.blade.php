@extends('admin.main-dashboard')

@section('adminSection')
    <section class="roleEdit--sec m-3 rounded p-4">
        <h1 class="text-white fs-5 m-0 text-uppercase position-relative mb-4">Edit Role</h1>

        <form action="{{ route('roles.update', $role->employee_role_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label text-white">Role Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update Role</button>
        </form>
    </section>
@endsection
