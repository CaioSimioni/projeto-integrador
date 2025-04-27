<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/patients/list', [PatientController::class, 'list'])->name('patients.list');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');

    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::patch('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
});
