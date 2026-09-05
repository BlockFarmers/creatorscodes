<?php

use Azuriom\Plugin\Creatorcodes\Http\Controllers\Admin\CommissionController;
use Azuriom\Plugin\Creatorcodes\Http\Controllers\Admin\CreatorCodeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CreatorCodeController::class, 'index'])->name('index');
Route::get('/create', [CreatorCodeController::class, 'create'])->name('create');
Route::post('/', [CreatorCodeController::class, 'store'])->name('store');
Route::get('/{creatorCode}/edit', [CreatorCodeController::class, 'edit'])->name('edit');
Route::put('/{creatorCode}', [CreatorCodeController::class, 'update'])->name('update');
Route::delete('/{creatorCode}', [CreatorCodeController::class, 'destroy'])->name('destroy');

Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions');
Route::post('/commissions/{commission}/mark-paid', [CommissionController::class, 'markPaid'])->name('commissions.mark-paid');
Route::post('/commissions/{commission}/paypal-payout', [CommissionController::class, 'payoutPaypal'])->name('commissions.paypal-payout');
