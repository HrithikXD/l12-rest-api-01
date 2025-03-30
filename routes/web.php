<?php

use App\Http\Controllers\Web\WebTaskController;
use App\Http\Controllers\Web\WebUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebUserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [WebUserController::class, 'login']);
    Route::get('/register', [WebUserController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [WebUserController::class, 'register']);
});

// Auth routes
Route::middleware('web.auth')->group(function () {

    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    Route::post('/logout', [WebUserController::class, 'logout'])->name('logout');

    // Profile routes
    Route::get('/profile', [WebUserController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [WebUserController::class, 'updateProfile'])->name('profile.update');

    // User management routes
    Route::get('/users', [WebUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [WebUserController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [WebUserController::class, 'updateUser'])->name('users.update');

    // Task routes
    Route::get('/tasks/update', [WebTaskController::class, 'edit'])->name('task.edit');
    Route::get('/tasks/create', [WebTaskController::class, 'create'])->name('task.create');
    Route::get('/tasks/{id}', [WebTaskController::class, 'show'])->name('tasks.show');
    Route::put('/tasks/complete', [WebTaskController::class, 'complete'])->name('task.complete');
    Route::put('/tasks/{id}', [WebTaskController::class, 'update'])->name('task.update');
    Route::delete('/tasks/delete', [WebTaskController::class, 'delete'])->name('task.delete');
    Route::post('/tasks', [WebTaskController::class, 'store'])->name('task.store');
    Route::get('/tasks', [WebTaskController::class, 'index'])->name('tasks.index');
});
