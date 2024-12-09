<?php

use App\Http\Controllers\Master\SupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix('master')->name('master.')->group(function () {
    // SUPPLIERS
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::post('import', [SupplierController::class, 'import'])->name('import');
        Route::resource('/', SupplierController::class);
    });
});
