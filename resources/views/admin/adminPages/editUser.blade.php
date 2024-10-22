@extends('admin.main-dashboard')

@section('adminSection')
<section class="editUser--sec m-3 rounded p-4">
    <h1 class="text-white mb-4 fs-4 text-white position-relative">
        @if (Auth::user()->is_admin)
            Edit User
        @else
            Edit Profile
        @endif
    </h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route(Auth::user()->is_admin ? 'admin.users.update' : 'users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Avatar -->
        <div class="form-group d-flex align-items-center mb-3 gap-3">
            @if ($user->avatar)
                <div class="changeProfile--img position-relative">
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                        class="img-thumbnail my-2 rounded-circle">
                    <label for="avatar" class="position-absolute top-0 end-0"><i class="fa-solid fa-camera"></i></label>
                </div>
            @endif
            <div class="changeProfile--btn">
                <input type="file" name="avatar" id="avatar" class="form-control btn">
            </div>
        </div>

        <div class="editUsers--inputGroup d-flex align-items-center gap-4 flex-wrap">
            <!-- Name -->
            <div class="form-group">
                <label for="name" class="text-white">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                    class="form-control" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="text-white">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                    class="form-control" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="text-white">Password</label>
                <input type="password" name="password" id="password" class="form-control"
                    placeholder="Leave blank to keep the current password">
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation" class="text-white">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>

            @if (Auth::user()->is_admin)
                <!-- Role -->
                <div class="form-group">
                    <label for="employee_role_id" class="text-white">Role</label>
                    <select name="employee_role_id" id="employee_role_id" class="form-control" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->employee_role_id }}"
                                {{ $role->employee_role_id == old('employee_role_id', $user->employee_role_id) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <!-- Skills -->
            <div class="form-group d-flex flex-column gap-1">
                <label for="skills" class="text-white">Skills</label>
                <select id="skills" name="skills[]" class="form-control select2" multiple="multiple">
                    @foreach ($skillsOptions as $skill)
                        <option value="{{ $skill }}"
                            {{ in_array($skill, old('skills', explode(',', $user->skills))) ? 'selected' : '' }}>
                            {{ $skill }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Languages -->
            <div class="form-group d-flex flex-column gap-1">
                <label for="languages" class="text-white">Languages</label>
                <select id="languages" name="languages[]" class="form-control select2" multiple="multiple">
                    @foreach ($languagesOptions as $language)
                        <option value="{{ $language }}"
                            {{ in_array($language, old('languages', explode(',', $user->languages))) ? 'selected' : '' }}>
                            {{ $language }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Country -->
            <div class="form-group">
                <label for="country" class="text-white me-3">Country</label>
                <div class="d-flex align-items-center position-relative">
                    <select id="country" class="form-control"></select>
                </div>
            </div>

            <!-- State -->
            <div class="form-group">
                <label for="state" class="text-white">State</label>
                <select id="state" name="state" class="form-control">
                    <option value="{{ old('state', $user->state) }}">Select your state</option>
                </select>
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="address" class="text-white">Address</label>
                <textarea id="address" name="address" class="form-control">{{ old('address', $user->address) }}</textarea>
            </div>

            <!-- Experience -->
            <div class="form-group">
                <label for="experience" class="text-white">Experience (years)</label>
                <input type="number" id="experience" name="experience" value="{{ old('experience', $user->experience) }}" class="form-control">
            </div>

            <!-- Age -->
            <div class="form-group">
                <label for="age" class="text-white">Age</label>
                <input type="number" id="age" name="age" value="{{ old('age', $user->age) }}" class="form-control">
            </div>

            <!-- Active Status -->
            <div class="form-group">
                <label for="active" class="text-white">Active</label>
                <select id="active" name="active" class="form-control">
                    <option value="1" {{ old('active', $user->active) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('active', $user->active) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="editUsers--btn mt-3 d-flex justify-content-end">
            <button type="submit" class="btn text-white">Update User</button>
        </div>
    </form>
</section>
@endsection
