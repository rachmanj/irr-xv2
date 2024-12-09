<?php

use App\Http\Controllers\Master\SupplierController;
use Illuminate\Support\Facades\Route;

Route::prefix('master')->name('master.')->group(function () {
    // SUPPLIERS
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::resource('/', SupplierController::class);
    });
});
