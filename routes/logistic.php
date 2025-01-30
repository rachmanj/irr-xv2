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
        Route::get('data', [LpdController::class, 'data'])->name('data');
        Route::get('/', [LpdController::class, 'index'])->name('index');
        Route::get('search', [LpdController::class, 'searchData'])->name('search');
        Route::get('create', [LpdController::class, 'create'])->name('create');
        Route::post('store', [LpdController::class, 'store'])->name('store');
        Route::get('ready-to-send', [LpdController::class, 'getReadyToSendDocuments'])->name('ready-to-send.data');
    });
});
