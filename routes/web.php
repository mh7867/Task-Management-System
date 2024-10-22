<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\TaskDiscussionController;
use App\Http\Controllers\TaskController;

// Redirect to login or dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->is_admin ? redirect()->route('admin.dashboard') : redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
});

// Auth Routes
Route::group(['middleware' => ['guest', 'redirectIfAdmin']], function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::resource('roles', RoleController::class);
});

// User Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('/user/dashboard', [AdminController::class, 'index'])->name('user.dashboard');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
});

// Task Routes (Both Admin and User can access)
Route::group(['middleware' => ['auth']], function () {
    Route::post('tasks/{task}/discussions', [TaskDiscussionController::class, 'store'])->name('tasks.discussions.store');
    Route::resource('tasks', TaskController::class);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/tasks/{task}/efforts', [TaskController::class, 'getTaskEfforts'])->name('tasks.getEfforts');
    Route::post('/tasks/{task}/start-timer', [TaskController::class, 'startTimer'])->name('tasks.startTimer');
    Route::post('/tasks/{task}/stop-timer', [TaskController::class, 'stopTimer'])->name('tasks.stopTimer');
    Route::post('/tasks/{task}/save-effort', [TaskController::class, 'saveEffort'])->name('tasks.saveEffort');
    Route::get('/my-tasks', [TaskController::class, 'assignedTasks'])->name('tasks.assigned');
});
