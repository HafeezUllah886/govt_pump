<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\bills;
use Illuminate\Http\Request;

class BillsSummaryController extends Controller
{
    public function index()
    {
        $departments = accounts::active()->get();
        return view('summary.index',compact('departments'));
    }

    public function details(Request $request)
    {
        $department = $request->department;
        $month = $request->month;
        $month_name = date('F - Y', strtotime($month));

        $bills = bills::where('department_id',$department)->where('month',$month_name)->get();
        $department = accounts::find($department);
        return view('summary.view',compact('department','month','month_name','bills'));
    }
}
