@extends('admin.main-dashboard')

@section('adminSection')

<section class="userCreate--sec m-3 rounded p-4">
    
<h1 class="text-white mb-4 fs-5 text-uppercase position-relative">Create User</h1>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="d-flex align-items-center flex-wrap gap-3">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
        </div>
    
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
        </div>
    
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
    
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>
     
        <div class="form-group w-100 mb-3">
            <label for="employee_role_id">Role</label>
            <select name="employee_role_id" id="employee_role_id" class="form-control" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->employee_role_id }}" {{ old('employee_role_id') == $role->employee_role_id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group d-flex flex-column mb-3">
        <label for="avatar" class="fs-4">Avatar</label>
        <input type="file" name="avatar" id="avatar" class="form-control-file btn">
    </div>

    <div class="form-group form-check mb-3">
        <input type="checkbox" name="is_admin" id="is_admin" class="form-check-input" value="1" {{ old('is_admin') ? 'checked' : '' }}>
        <label for="is_admin" class="form-check-label">Is Admin</label>
    </div>

    <button type="submit" class="btn">Create User</button>
</form>
</section>

@endsection
