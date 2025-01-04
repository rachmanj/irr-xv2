<?php

use App\Http\Controllers\Accounting\AdditionalDocumentController;
use App\Http\Controllers\Accounting\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('accounting')->name('accounting.')->group(function () {
    // INVOICES
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('data', [InvoiceController::class, 'data'])->name('data');
        Route::get('/search', [InvoiceController::class, 'searchInvoices'])->name('search');
        Route::resource('/', InvoiceController::class)->parameters(['' => 'invoice']);
    });


    // ADDITIONAL DOCUMENTS
    Route::prefix('additional-documents')->name('additional-documents.')->group(function () {
        Route::get('data', [AdditionalDocumentController::class, 'data'])->name('data');
        Route::get('search', [AdditionalDocumentController::class, 'searchData'])->name('search');
        Route::resource('/', AdditionalDocumentController::class)->parameters(['' => 'additionalDocument']);
    });
});
