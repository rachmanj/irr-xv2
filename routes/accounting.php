<?php

use App\Http\Controllers\Accounting\AdditionalDocumentController;
use App\Http\Controllers\Accounting\InvoiceController;
use App\Http\Controllers\Accounting\SpiController;
use Illuminate\Support\Facades\Route;

Route::prefix('accounting')->name('accounting.')->group(function () {
    // INVOICES
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('data', [InvoiceController::class, 'data'])->name('data');
        Route::get('/search', [InvoiceController::class, 'searchInvoices'])->name('search');
        Route::resource('/', InvoiceController::class)->parameters(['' => 'invoice']);

        // Attachment routes
        Route::post('{invoice}/upload-attachments', [InvoiceController::class, 'uploadAttachments'])
            ->name('upload-attachments');

        Route::get('{invoice}/attachments', [InvoiceController::class, 'getAttachments'])
            ->name('get-attachments');
    });

    Route::delete('attachments/{attachment}', [InvoiceController::class, 'deleteAttachment'])
        ->name('attachments.destroy');

    // ADDITIONAL DOCUMENTS
    Route::prefix('additional-documents')->name('additional-documents.')->group(function () {
        Route::get('data', [AdditionalDocumentController::class, 'data'])->name('data');
        Route::get('search', [AdditionalDocumentController::class, 'searchData'])->name('search');
        Route::resource('/', AdditionalDocumentController::class)->parameters(['' => 'additionalDocument']);
    });

    // SPI
    Route::prefix('spi')->name('spi.')->group(function () {
        Route::get('data', [SpiController::class, 'data'])->name('data');
        Route::get('search', [SpiController::class, 'searchData'])->name('search');
        Route::resource('/', SpiController::class)->parameters(['' => 'spi']);
    });
});
