<?php

use App\Http\Controllers\billsController;
use App\Http\Controllers\BillsSummaryController;
use App\Http\Controllers\SalePaymentsController;
use App\Http\Middleware\confirmPassword;
use App\Models\products;
use App\Models\Vehicles;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::resource('bills', billsController::class);

    Route::get('/bills/getvehicles/{id}',function($id){

        $vehicles = Vehicles::where('department_id', $id)->select('id as value','r_no as text')->get();
        return response()->json($vehicles);
        
    });

    Route::get('/summary',[BillsSummaryController::class,'index'])->name('summary.index');
    Route::get('/summary/details',[BillsSummaryController::class,'details'])->name('summary.details');

});
