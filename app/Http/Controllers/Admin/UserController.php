<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    public function index()
    {
        $users = User::with('employeeRole')->get();
        if (auth()->check() && auth()->user()->is_admin) {
            return view('admin.adminPages.usersIndex', compact('users'));
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    public function create()
    {
        $roles = EmployeeRole::all();
        if (auth()->check() && auth()->user()->is_admin) {
            return view('admin.adminPages.users', compact('roles'));
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'employee_role_id' => 'required|exists:employee_roles,employee_role_id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'employee_role_id' => $request->employee_role_id,
            'is_admin' => $request->is_admin ?? 0,
            'avatar' => $avatarPath
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if (Gate::denies('update', $user)) {
            abort(403, 'Unauthorized');
        }

        $skillsOptions = ['PHP', 'JavaScript', 'Laravel', 'Vue.js', 'Node.js', 'Angular.js', 'Angular', 'HTML', 'CSS', 'Tailwind CSS', 'AWS', 'UI/UX', 'Adobe PhotoShop', 'Adobe Ilustrator', 'C#', 'C++', 'Blander', 'Maya', 'Canva', 'Unreal Engine', 'Unity Engine', 'CodeIngiter', 'Java', 'Flutter', 'Kotlin', 'Swift', 'TypeScript', 'JSON'];
        $languagesOptions = ['English', 'Spanish', 'French', 'German', 'Urdu', 'Hindi'];

        $roles = EmployeeRole::all();
        return view('admin.adminPages.editUser', compact('user', 'roles', 'skillsOptions', 'languagesOptions'));
    }

    public function update(Request $request, User $user)
    {
        if (Gate::denies('update', $user)) {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'employee_role_id' => 'nullable|exists:employee_roles,employee_role_id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'skills' => 'nullable|array',
            'languages' => 'nullable|array',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'age' => 'nullable|integer|min:0',
        ]);

        $data['skills'] = isset($data['skills']) ? implode(',', $data['skills']) : '';
        $data['languages'] = isset($data['languages']) ? implode(',', $data['languages']) : '';

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        if (auth()->user()->is_admin) {
         return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        }else{
         return redirect()->route('user.dashboard')->with('success', 'Profile successfully updated.');
        }
    }


    public function destroy(User $user)
    {
        if (Gate::denies('delete', $user)) {
            abort(403, 'Unauthorized');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

}

