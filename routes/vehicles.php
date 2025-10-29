<?php

use App\Http\Controllers\AccountAdjustmentController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\ExpenseCategoriesController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PaymentsReceivingController;
use App\Http\Controllers\RouteExpensesController;
use App\Http\Controllers\StaffAmountAdjustmentController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\VehiclesController;
use App\Http\Middleware\confirmPassword;
use App\Models\attachment;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
   Route::resource('vehicles', VehiclesController::class);

});
