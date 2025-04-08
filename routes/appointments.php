<?php

use App\Http\Controllers\AppointmentsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {
    Route::get('/appointments', [AppointmentsController::class, 'index'])->name('appointments.index');
    Route::post('/appointments', [AppointmentsController::class, 'store'])->name('appointments.store');
    Route::patch('/appointments/{appointment}', [AppointmentsController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentsController::class, 'destroy'])->name('appointments.destroy');
});
