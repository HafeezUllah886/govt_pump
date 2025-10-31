<?php

use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalePaymentsController;
use App\Http\Middleware\confirmPassword;
use App\Models\products;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('sale', SaleController::class);

    Route::get("sales/getproduct/{id}", [SaleController::class, 'getSignleProduct']);
    Route::get("sales/delete/{id}", [SaleController::class, 'destroy'])->name('sale.delete')->middleware(confirmPassword::class);

    Route::get("sales/get_by_code/{code}", function($code){
        $product = products::where('code', $code)->first()->id ?? "Not Found";
        return $product;
    });

});
