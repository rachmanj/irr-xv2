<?php

use App\Http\Controllers\Accounting\AdditionalDocumentController;
use App\Http\Controllers\Accounting\DeliveryController;
use App\Http\Controllers\Accounting\InvoiceController;
use App\Http\Controllers\Accounting\LpdController;
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
        Route::get('ready-to-deliver/data', [SpiController::class, 'readyToDeliverData'])
            ->name('ready-to-deliver.data');
        Route::get('search', [SpiController::class, 'searchData'])->name('search');
        Route::get('/{id}/print-preview', [SpiController::class, 'printPreview'])->name('print-preview');
        Route::get('/{id}/print-content', [SpiController::class, 'printContent'])->name('print-content');
        Route::resource('/', SpiController::class)->parameters(['' => 'spi']);
        Route::post('/{id}/send', [SpiController::class, 'send'])->name('send');
    });

    // LPD
    Route::prefix('lpd')->name('lpd.')->group(function () {
        Route::get('data', [LpdController::class, 'data'])->name('data');
        Route::get('search', [LpdController::class, 'searchData'])->name('search');
        Route::resource('/', LpdController::class)->parameters(['' => 'lpd']);
    });

    // DELIVERIES -- NOT USEd
    Route::prefix('deliveries')->name('deliveries.')->group(function () {
        Route::resource('/', DeliveryController::class)->parameters(['' => 'delivery']);
        Route::post('{delivery}/receive', [DeliveryController::class, 'markAsReceived'])
            ->name('receive');
        Route::get('{delivery}/show', [DeliveryController::class, 'show'])
            ->name('show');
        Route::get('/ready-to-deliver/data', [DeliveryController::class, 'readyToDeliverData'])
            ->name('ready-to-deliver.data');
    });
});
