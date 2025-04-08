<?php

use App\Http\Controllers\MaterialController;
use App\Models\Material;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {
    Route::get('inventory', function () {
        return Inertia::render('inventory/dashboard', [
            'materialsQuantity' => Material::count(),
        ]);
    })->name('inventory');

    Route::get('inventory/materials', [MaterialController::class, 'index'])->name('materials');
    Route::post('inventory/materials', [MaterialController::class, 'store'])->name('material.create');
    Route::patch('inventory/materials/{material}', [MaterialController::class, 'update'])->name('material.update');
    Route::delete('inventory/materials/{material}', [MaterialController::class, 'destroy'])->name('material.destroy');
    Route::get('inventory/materials/quantity', [MaterialController::class, 'materialsQuantity'])->name('material.quantity');
});
