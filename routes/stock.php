<?php

use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockTransferController;
use App\Http\Middleware\confirmPassword;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('products/stock/{id}/{from}/{to}/{warehouse}', [StockController::class, 'show'])->name('stockDetails');
    Route::resource('product_stock', StockController::class);

    Route::resource('stockAdjustments', StockAdjustmentController::class);
    Route::get('stockAdjustment/delete/{ref}', [StockAdjustmentController::class, 'destroy'])->name('stockAdjustment.delete')->middleware(confirmPassword::class);

    Route::get('stockTransfers/delete/{ref}', [StockTransferController::class, 'destroy'])->name('stockTransfers.delete')->middleware(confirmPassword::class);
    Route::resource('stockTransfers', StockTransferController::class);
});

