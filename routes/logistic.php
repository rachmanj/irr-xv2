<?php

use App\Http\Controllers\Accounting\LpdController;
use App\Http\Controllers\Logistic\AddocController;
use Illuminate\Support\Facades\Route;

Route::prefix('logistic')->name('logistic.')->group(function () {
    Route::prefix('addoc')->name('addoc.')->group(function () {
        Route::get('/', [AddocController::class, 'index'])->name('index');
    });

    Route::prefix('lpd')->name('lpd.')->group(function () {
        Route::get('/', [LpdController::class, 'index'])->name('index');
    });
});
