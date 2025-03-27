<?php

use App\Http\Controllers\Api\TaskApiController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

#Public Route
Route::post('/users/login', [UserApiController::class, 'login'])->name('users.login');
Route::post('/users/register', [UserApiController::class, 'register'])->name('users.register');

#User Route
Route::middleware(['auth.jwt'])->group(function () {
    Route::get('/users/logout', [UserApiController::class, 'logout'])->name('users.logout');
    Route::get('/users/profile',[UserApiController::class, 'profile'])->name('users.profile');
    Route::put('/users/profile/update',[UserApiController::class, 'updateProfile'])->name('users.profile.update');
    Route::get('/tasks/user', [TaskApiController::class, 'getUserTask'])->name('tasks.user.task');
    Route::resource('tasks', TaskApiController::class);
});

#Admin Route
Route::middleware(['auth.admin'])->group(function () {
    Route::get('/tasks', [TaskApiController::class, 'index'])->name('tasks.index');
    Route::resource('users', UserApiController::class);
    Route::get('tasks/admin/{user}', [TaskApiController::class, 'getTasksOfUser'])->name('admin.tasks');
});
