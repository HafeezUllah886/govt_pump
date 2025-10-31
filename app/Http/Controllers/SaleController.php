<?php

namespace App\Http\Controllers;

use App\Http\Middleware\confirmPassword;
use App\Models\accounts;
use App\Models\categories;
use App\Models\products;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\stock;
use App\Models\transactions;
use App\Models\units;
use App\Models\Vehicles;
use App\Models\warehouses;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class SaleController extends Controller
{
    public function __construct()
    {
        // Apply middleware to the edit method
        $this->middleware(confirmPassword::class)->only('edit');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->from ?? firstDayOfMonth();
        $to = $request->to ?? date('Y-m-d');
        $sales = sale::whereBetween('date', [$from, $to])->orderBy('id', 'desc')->get();
        $departments = accounts::active()->get();
        return view('sale.index', compact('sales', 'from', 'to', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $products = products::active()->get();
        $department = accounts::find($request->department_id);
        $vehicles = Vehicles::where('department_id', $request->department_id)->get();
       
        return view('sale.create', compact('products', 'department', 'vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try
        {
            if($request->isNotFilled('id'))
            {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
            $ref = getRef();
            $sale = sale::create(
                [
                  'department_id'  => $request->department_id,
                  'vehicle_id'     => $request->vehicle_id,
                  'vouchar'        => $request->vouchar,
                  'date'           => $request->date,
                  'notes'          => $request->notes,
                  'refID'          => $ref,
                ]
            );

            $ids = $request->id;
            $total = 0;
            $note_details = "";
            foreach($ids as $key => $id)
            {
                if($request->qty[$key] > 0)
                {
                $qty = $request->qty[$key]; 
                $price = $request->price[$key];
                $amount = $price * $qty;
                $total += $amount;

                sale_details::create(
                    [
                        'sale_id'       => $sale->id,
                        'product_id'    => $id,
                        'department_id'  => $request->department_id,
                        'vehicle_id'     => $request->vehicle_id,
                        'price'         => $price,
                        'qty'           => $qty,
                        'vouchar'       => $request->vouchar,
                        'amount'        => round($amount),
                        'date'          => $request->date,
                        'refID'         => $ref,
                    ]
                );
                $product = products::find($id);
                $note_details .= $qty . "x " . $product->name . " at " . number_format($price) . "<br>";
                }
            }

            $sale->update(
                [
                    'total' => $total,
                ]
            );
            createTransaction($request->department_id, $request->vehicle_id, $request->date, $total, 0, "Sale No. $sale->id Vouchar No. $sale->vouchar <br> $note_details", $ref);
            DB::commit();
            return back()->with('success', "Sale Created");

        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(sale $sale)
    {
        return view('sale.print', compact('sale'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sale $sale)
    {
        $products = products::orderby('name', 'asc')->get();
        $vehicles = Vehicles::where('department_id', $sale->department_id)->get();
        return view('sale.edit', compact('products', 'vehicles', 'sale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sale $sale)
    {
        try
        {
            if($request->isNotFilled('id'))
            {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
          
          
            transactions::where('refID', $sale->refID)->delete();

            $sale->update(
                [
                  'department_id'  => $request->department_id,
                  'vehicle_id'     => $request->vehicle_id,
                  'vouchar'        => $request->vouchar,
                  'date'           => $request->date,
                  'notes'          => $request->notes,
                ]
            );

            $ids = $request->id;
            $ref = $sale->refID;
            $total = 0;
            $note_details = "";
            foreach($ids as $key => $id)
            {
                if($request->qty[$key] > 0)
                {
                $qty = $request->qty[$key]; 
                $price = $request->price[$key];
                $amount = $price * $qty;
                $total += $amount;

                sale_details::create(
                    [
                        'sale_id'       => $sale->id,
                        'product_id'    => $id,
                        'department_id'  => $request->department_id,
                        'vehicle_id'     => $request->vehicle_id,
                        'price'         => $price,
                        'qty'           => $qty,
                        'vouchar'       => $request->vouchar,
                        'amount'        => round($amount),
                        'date'          => $request->date,
                        'refID'         => $ref,
                    ]
                );
                $product = products::find($id);
                $note_details .= $qty . "x " . $product->name . " at " . number_format($price) . "<br>";
                }
            }

            $sale->update(
                [
                    'total' => $total,
                ]
            );
            createTransaction($request->department_id, $request->vehicle_id, $request->date, $total, 0, "Sale No. $sale->id Vouchar No. $sale->vouchar <br> $note_details", $ref);
            DB::commit();
            session()->forget('confirmed_password');
            return to_route('sale.index')->with('success', "Sale Updated");
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        try
        {
            DB::beginTransaction();
            $sale = sale::find($id);
            transactions::where('refID', $sale->refID)->delete();
            $sale->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return to_route('sale.index')->with('success', "Sale Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return to_route('sale.index')->with('error', $e->getMessage());
        }
    }

    public function getSignleProduct($id)
    {
        $product = products::find($id);
        return $product;
    }
}
