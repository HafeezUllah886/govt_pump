<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\stock;
use App\Models\units;
use App\Models\warehouses;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = products::all();
        $warehouses = warehouses::all();
       
        return view('stock.index', compact('products', 'warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show($id, $from, $to, $warehouse)
    {
        $product = products::find($id);

      if($warehouse == 'All'){
            $stocks = stock::where('product_id', $id)->whereBetween('date', [$from, $to])->get();
             $pre_cr = stock::where('product_id', $id)->whereDate('date', '<', $from)->sum('cr');
            $pre_db = stock::where('product_id', $id)->whereDate('date', '<', $from)->sum('db');

            $cur_cr = stock::where('product_id', $id)->sum('cr');
            $cur_db = stock::where('product_id', $id)->sum('db');
            $warehouse_name = 'All';
       
      }else{
         $stocks = stock::where('product_id', $id)->whereBetween('date', [$from, $to])->where('warehouse_id', $warehouse)->get();
             $pre_cr = stock::where('product_id', $id)->whereDate('date', '<', $from)->where('warehouse_id', $warehouse)->sum('cr');
            $pre_db = stock::where('product_id', $id)->whereDate('date', '<', $from)->where('warehouse_id', $warehouse)->sum('db');

            $cur_cr = stock::where('product_id', $id)->where('warehouse_id', $warehouse)->sum('cr');
            $cur_db = stock::where('product_id', $id)->where('warehouse_id', $warehouse)->sum('db');
            $warehouse_name = warehouses::find($warehouse)->name;
      }

           
        $pre_balance = $pre_cr - $pre_db;
        $cur_balance = $cur_cr - $cur_db;

        

        return view('stock.details', compact('product', 'pre_balance', 'cur_balance', 'stocks', 'from', 'to', 'warehouse_name'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(stock $stock)
    {
        //
    }
}
