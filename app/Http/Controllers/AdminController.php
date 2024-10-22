<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class AdminController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $users = User::with('employeeRole')->get();
        return view('admin.adminPages.dashboard', compact('users'));


    }
}
