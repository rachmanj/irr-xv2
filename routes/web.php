<?php

use App\Http\Controllers\Accounting\AdditionalDocumentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accounting\InvoiceController;
use App\Http\Controllers\Master\SupplierController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Accounting\DeliveryController;

Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'authenticate')->name('authenticate');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'index')->name('register');
        Route::post('/register', 'store')->name('register.store');
    });
});

// middleware('auth') means that the user must be authenticated to access the route
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/test', [TestController::class, 'index'])->name('test');

    require __DIR__ . '/admin.php';
    require __DIR__ . '/accounting.php';
    require __DIR__ . '/finance.php';
    require __DIR__ . '/logistic.php';
    require __DIR__ . '/master.php';
});

Route::get('/check-invoice-number', [InvoiceController::class, 'checkInvoiceNumber'])->name('check.invoice.number');
Route::get('/get-payment-project', [SupplierController::class, 'getPaymentProject'])->name('get.payment.project');
Route::get('/get-project-location', [InvoiceController::class, 'getProjectLocation'])->name('get.project.location');
Route::get('/check-addoc-combination', [AdditionalDocumentController::class, 'checkDocumentCombination'])->name('check.addoc.combination');
Route::get('/search-invoices-by-po', [AdditionalDocumentController::class, 'searchInvoicesByPo'])->name('search.invoices.by.po');
Route::get('/search-additional-documents-by-po', [AdditionalDocumentController::class, 'searchAdditionalDocumentsByPo'])->name('search_addocs_by_po');
Route::get('/additional-documents/search', [AdditionalDocumentController::class, 'search'])
    ->name('additional-documents.search');
Route::get('/accounting/invoices/search', [InvoiceController::class, 'search'])
    ->name('accounting.invoices.search');
