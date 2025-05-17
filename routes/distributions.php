<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentDistributionController;
use App\Http\Controllers\Api\DocumentDistributionController as ApiDocumentDistributionController;

// Document Distribution Routes
Route::prefix('distributions')->name('document-distributions.')->group(function () {
    Route::get('/', [DocumentDistributionController::class, 'index'])->name('index');
    Route::get('/document', [DocumentDistributionController::class, 'document'])->name('document');
    Route::get('/create', [DocumentDistributionController::class, 'create'])->name('create');
    Route::post('/', [DocumentDistributionController::class, 'store'])->name('store');
    Route::get('/history', [DocumentDistributionController::class, 'history'])->name('history');
    Route::get('/search-history', [DocumentDistributionController::class, 'searchHistory'])->name('search-history');
    Route::get('/search', [DocumentDistributionController::class, 'search'])->name('search');
    Route::post('/{documentDistribution}/receive', [DocumentDistributionController::class, 'receive'])->name('receive');
    Route::post('/{documentDistribution}/reject', [DocumentDistributionController::class, 'reject'])->name('reject');
    Route::get('/{documentDistribution}', [DocumentDistributionController::class, 'show'])->name('show');
});

// API Routes for Document Distribution
Route::prefix('api/distributions')->name('api.distributions.')->group(function () {
    Route::get('/search-documents', [ApiDocumentDistributionController::class, 'searchDocuments'])->name('search-documents');
    Route::get('/suppliers', [ApiDocumentDistributionController::class, 'getSuppliers'])->name('suppliers');
    Route::get('/document-types', [ApiDocumentDistributionController::class, 'getDocumentTypes'])->name('document-types');
}); 