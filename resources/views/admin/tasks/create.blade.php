@extends('admin.main-dashboard')

@section('adminSection')
    <section class="userList--sec m-3 rounded p-4">
        <div class="container">
            <h1 class="text-white fs-5 mb-4 text-uppercase position-relative">Create Task</h1>
            
            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Task Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Task Name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="description">Task Description</label>
                    <x-forms.tinymce-editor name="description" value="{{ old('description') }}" />
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="priority">Priority</label>
                    <input type="text" name="priority" class="form-control" placeholder="Priority" value="{{ old('priority') }}" required>
                    @error('priority')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                    @error('end_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="status">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in Progress" {{ old('status') == 'inprogress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="assigned_to">Assign To</label>
                    <select name="assigned_to[]" class="form-control select2" multiple="multiple" required>
                        @foreach($filteredUsers as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, old('assigned_to', [])) ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->employeeRole->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="files">Upload Files</label>
                    <input type="file" name="files[]" class="form-control btn" multiple>
                    @error('files')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Create Task</button>
            </form>
        </div>
    </section>
@endsection
