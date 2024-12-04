<?php

use App\Http\Controllers\Accounting\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('accounting')->name('accounting.')->group(function () {
    // INVOICES
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::resource('/', InvoiceController::class);
    });
});
