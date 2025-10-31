<?php

namespace App\Http\Controllers;

use App\Models\bills;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\sale_details;
use App\Models\Vehicles;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $department = $request->department ?? 'All';
       if($department == 'All'){
           $bills = bills::orderBy('id', 'desc')->get();
       }else{
           $bills = bills::where('department_id', $department)->orderBy('id', 'desc')->get();
       }
       $departments = accounts::active()->get();
       return view('bills.index', compact('bills', 'departments', 'department'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $department_id = $request->department_id;
        $vehicle_id = $request->vehicle_id;
        $month = $request->month;
        $start_date = Carbon::parse($month)->startOfMonth()->format('Y-m-d');
        $end_date = Carbon::parse($month)->endOfMonth()->format('Y-m-d');
        $month_name = Carbon::parse($month)->format('F - Y');

        $check = bills::where('vehicle_id', $vehicle_id)->where('month', $month_name)->first();
        if($check){
            return redirect()->back()->with('error', 'Bill already generated for this vehicle and month');
        }
        
       
        $vehicle = Vehicles::find($vehicle_id);
        $department = accounts::find($department_id);
        $bill_no = Carbon::parse($month)->format('Ym') . $department_id . $vehicle_id;

        $bill = bills::create([
            'bill_no' => $bill_no,
            'department_id' => $department_id,
            'vehicle_id' => $vehicle_id,
            'month' => $month_name,
            'total' => 0,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'bill_date' => $end_date,
        ]);
       
        return to_route('bills.show', $bill->id)->with('success', 'Bill generated successfully');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bill = bills::find($id);
         $sales = sale_details::where('vehicle_id', $bill->vehicle_id)->whereBetween('date', [$bill->start_date, $bill->end_date])->get();
         $bill->update([
            'total' => $sales->sum('amount'),
         ]);
         return view('bills.view', compact('bill', 'sales'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bill = bills::find($id);
         $sales = sale_details::where('vehicle_id', $bill->vehicle_id)->whereBetween('date', [$bill->start_date, $bill->end_date])->get();
         $bill->update([
            'total' => $sales->sum('amount'),
         ]);
         return view('bills.edit', compact('bill', 'sales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, bills $bills)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(bills $bills)
    {
        //
    }
}
