<?php


use App\Http\Controllers\Logistic\AddocController;
use App\Http\Controllers\Logistic\LpdController;
use Illuminate\Support\Facades\Route;

Route::prefix('logistic')->name('logistic.')->group(function () {
    Route::prefix('addoc')->name('addoc.')->group(function () {
        Route::get('data', [AddocController::class, 'data'])->name('data');
        Route::get('/', [AddocController::class, 'index'])->name('index');
        Route::get('search', [AddocController::class, 'searchData'])->name('search');
        Route::get('edit/{id}', [AddocController::class, 'edit'])->name('edit');
    });

    Route::prefix('lpd')->name('lpd.')->group(function () {
        Route::get('/', [LpdController::class, 'index'])->name('index');
        Route::get('/data', [LpdController::class, 'data'])->name('data');
        Route::get('/ready-to-send/data', [LpdController::class, 'getReadyToSendDocuments'])->name('ready-to-send.data');
        Route::post('/', [LpdController::class, 'store'])->name('store');
        Route::get('/{id}', [LpdController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [LpdController::class, 'edit'])->name('edit');
        Route::put('/{id}', [LpdController::class, 'update'])->name('update');
        Route::get('/{id}/print', [LpdController::class, 'print'])->name('print');
        Route::post('/{id}/send', [LpdController::class, 'send'])->name('send');
        Route::delete('/{id}', [LpdController::class, 'destroy'])->name('destroy');
    });
});
