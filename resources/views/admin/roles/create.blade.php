@extends('admin.main-dashboard')

@section('adminSection')
    <section class="roleCreate--sec m-3 rounded p-4">
        <h1 class="text-white fs-5 m-0 text-uppercase position-relative mb-4">Create New Role</h1>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label text-white">Role Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Create Role</button>
        </form>
    </section>
@endsection
