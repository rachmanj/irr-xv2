<?php

use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\Master\AdditionalDocumentTypeController;
use App\Http\Controllers\Master\InvoiceTypeController;
use App\Http\Controllers\Master\ItoController;
use App\Http\Controllers\Master\UploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('master')->name('master.')->group(function () {
    // SUPPLIERS
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('data', [SupplierController::class, 'data'])->name('data');
        Route::post('import', [SupplierController::class, 'import'])->name('import');
        Route::resource('/', SupplierController::class);
    });

    // ADDITIONAL DOCUMENT TYPES
    Route::prefix('additional-document-types')->name('additional-document-types.')->group(function () {
        Route::get('data', [AdditionalDocumentTypeController::class, 'data'])->name('data');
        Route::resource('/', AdditionalDocumentTypeController::class)->parameters(['' => 'additionalDocumentType']);
    });

    // INVOICE TYPES
    Route::prefix('invoice-types')->name('invoice-types.')->group(function () {
        Route::get('data', [InvoiceTypeController::class, 'data'])->name('data');
        Route::resource('/', InvoiceTypeController::class)->parameters(['' => 'invoiceType']);
    });

    //UPLOAD
    Route::prefix('upload')->name('upload.')->group(function () {
        Route::get('/', [UploadController::class, 'index'])->name('index');
        Route::prefix('ito')->name('ito.')->group(function () {
            Route::get('/data', [ItoController::class, 'data'])->name('data');
            Route::post('/upload', [ItoController::class, 'upload'])->name('upload');
            Route::get('/search-data', [ItoController::class, 'searchData'])->name('search.data');
        });
    });
});
