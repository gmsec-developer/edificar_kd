<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'permission:users.view'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
});

Route::middleware(['auth', 'permission:users.create'])->group(function () {
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
});

Route::middleware(['auth', 'permission:users.edit'])->group(function () {
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
});

Route::middleware(['auth', 'permission:users.delete'])->group(function () {
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth', 'permission:users.toggle'])->group(function () {
    Route::post('users/{id}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
});

Route::middleware(['auth', 'permission:users.reset'])->group(function () {
    Route::post('users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset');
});

Route::middleware(['auth', 'permission:roles.view'])->group(function () {
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
});

Route::middleware(['auth', 'permission:roles.create'])->group(function () {
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
});

Route::middleware(['auth', 'permission:roles.edit'])->group(function () {
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
});

Route::middleware(['auth', 'permission:roles.delete'])->group(function () {
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
});

Route::middleware(['auth', 'permission:settings.view'])
    ->get('activity-log', [App\Http\Controllers\ActivityLogController::class, 'index'])
    ->name('activity.index');

require __DIR__.'/auth.php';

Route::post('notifications/read', function () {
    \Spatie\Activitylog\Models\Activity::where('causer_id', auth()->id())
        ->update(['is_read' => true]);

    return back();
})->name('notifications.read')->middleware('auth');

Route::middleware(['auth', 'permission:settings.edit'])->group(function () {
    Route::get('settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});