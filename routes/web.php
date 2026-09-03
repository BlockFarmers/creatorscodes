<?php

use Azuriom\Plugin\Creatorcodes\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [SupportController::class, 'show'])->name('support');
    Route::post('/', [SupportController::class, 'update'])->name('support.update');
    Route::delete('/', [SupportController::class, 'destroy'])->name('support.destroy');
});
