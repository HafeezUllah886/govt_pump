<?php

use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('products', ProductsController::class);
        
});

