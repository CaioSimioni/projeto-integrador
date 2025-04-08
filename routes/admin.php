<?php

use App\Http\Controllers\Admin\AdminUsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin', [AdminUsersController::class, 'adminPanel'])->name('admin');
    Route::get('admin/users', [AdminUsersController::class, 'users'])->name('admin.users');

    Route::post('admin/users', [AdminUsersController::class, 'create'])->name('user.create');
    Route::patch('admin/users/{user}', [AdminUsersController::class, 'update'])->name('user.update');
    Route::delete('admin/users/{user}', [AdminUsersController::class, 'destroy'])->name('user.destroy');

    Route::get('admin/users/quantity', [AdminUsersController::class, 'usersQuantity'])->name('user.quantity');
});
