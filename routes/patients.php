<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Página inicial dos pacientes
    Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');

    // Listagem de pacientes
    Route::get('/patients/list', [PatientController::class, 'list'])->name('patients.list');

    // Página de criação de paciente
    Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');

    // Exibe os exames de um paciente
    Route::get('/patients/{patient}/exams', [PatientController::class, 'exams'])->name('patients.exams');

    // Salva um novo paciente
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');

    // Atualiza os dados de um paciente
    Route::patch('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');

    // Exclui um paciente
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
});

