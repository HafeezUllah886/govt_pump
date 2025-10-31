<?php

use App\Http\Controllers\dashboardController;
use Illuminate\Support\Facades\Route;


require __DIR__ . '/auth.php';
require __DIR__ . '/setups.php';
require __DIR__ . '/finance.php';
require __DIR__ . '/sale.php';
require __DIR__ . '/bills.php';
require __DIR__ . '/reports.php';
require __DIR__ . '/vehicles.php';

Route::middleware('auth')->group(function () {

    Route::get('/', [dashboardController::class, 'index'])->name('dashboard');


});


