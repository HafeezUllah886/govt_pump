<?php

namespace App\Http\Controllers;

use App\Http\Middleware\confirmPassword;
use App\Models\accounts;
use App\Models\categories;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\purchase_payments;
use App\Models\stock;
use App\Models\transactions;
use App\Models\units;
use App\Models\warehouses;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class PurchaseController extends Controller
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
        $purchases = purchase::whereBetween('date', [$from, $to])->get();
        return view('purchase.index', compact('purchases', 'from', 'to'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = products::active()->get();
        $vendors = accounts::vendor()->get();
        $accounts = accounts::business()->get();
        $warehouses = warehouses::all();
        return view('purchase.create', compact('products', 'vendors', 'accounts', 'warehouses'));
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
            $purchase = purchase::create(
                [
                  'vendor_id'        => $request->vendorID,
                  'date'            => $request->date,
                  'notes'           => $request->notes,
                  'vendorName'      => $request->vendorName,
                  'payment_status'  => $request->status,
                  'warehouse_id'    => $request->warehouse,
                  'refID'           => $ref,
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

                purchase_details::create(
                    [
                        'purchase_id'    => $purchase->id,
                        'product_id'     => $id,
                        'price'         => $price,
                        'qty'           => $qty,
                        'amount'        => $amount,
                        'date'          => $request->date,
                        'refID'         => $ref,
                    ]
                );

                $product = products::find($id);
                $vendor = accounts::find($request->vendorID);

                $note_details .= $qty . "x " . $product->name . " at " . number_format($price) . "<br>";
                createStock($id, $qty, 0, $request->date, "Purchased from $vendor->title at $price amount $amount Notes: $request->notes", $ref, $request->warehouse);
                }
            }
            $purchase->update(
                [
                    'total' => $total,
                ]
            );
            if($request->status == 'paid')
            {
                createTransaction($request->vendorID, $request->date, $total, $total, "Payment of Purchase No. $purchase->id Notes: $request->notes <br> $note_details", $ref, 'Purchase');
                createTransaction($request->accountID, $request->date, 0, $total, "Payment of Purchase No. $purchase->id Notes: $request->notes <br> $note_details", $ref, 'Purchase');
            }
            else
            {
                createTransaction($request->vendorID, $request->date, 0, $total, "Pending Amount of Purchase No. $purchase->id Notes: $request->notes <br> $note_details", $ref, 'Purchase');
            }
            DB::commit();
            return back()->with('success', "Purchase Created");
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
    public function show(purchase $purchase)
    {
        return view('purchase.view', compact('purchase'));
    }

  

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(purchase $purchase)
    {
        $products = products::orderby('name', 'asc')->get();
        $vendors = accounts::vendor()->get();
        $accounts = accounts::business()->get();
        $warehouses = warehouses::all();
        return view('purchase.edit', compact('products', 'vendors', 'accounts', 'purchase', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, purchase $purchase)
    {
        try
        {
            if($request->isNotFilled('id'))
            {
                throw new Exception('Please Select Atleast One Product');
            }
            DB::beginTransaction();
          
            foreach($purchase->details as $product)
            {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            transactions::where('refID', $purchase->refID)->delete();

            $purchase->update(
                [
                    'vendor_id'        => $request->vendorID,
                    'date'            => $request->date,
                    'notes'           => $request->notes,
                    'vendorName'      => $request->vendorName,
                    'payment_status'  => $request->status,
                    'warehouse_id'    => $request->warehouse,
                ]
            );

            $ids = $request->id;
            $ref = $purchase->refID;

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

                purchase_details::create(
                    [
                        'purchase_id'    => $purchase->id,
                        'product_id'     => $id,
                        'price'         => $price,
                        'qty'           => $qty,
                        'amount'        => $amount,
                        'date'          => $request->date,
                        'refID'         => $ref,
                    ]
                );
                $product = products::find($id);
                 $vendor = accounts::find($request->vendorID);

                $note_details .= $qty . "x " . $product->name . " at " . number_format($price) . "<br>";
                createStock($id, $qty, 0, $request->date, "Purchased from $vendor->title at $price amount $amount Notes: $request->notes", $ref, $request->warehouse);

                }
            }
        
            $purchase->update(
                [

                    'total'       => $total,
                ]
            );

             if($request->status == 'paid')
            {   
                createTransaction($request->vendorID, $request->date, $total, $total, "Payment of Purchase No. $purchase->id <br> $note_details", $ref, 'Purchase');
                createTransaction($request->accountID, $request->date, 0, $total, "Payment of Purchase No. $purchase->id <br> $note_details", $ref, 'Purchase');
            }
            else
            {
                createTransaction($request->vendorID, $request->date, 0, $total, "Pending Amount of Purchase No. $purchase->id <br> $note_details", $ref, 'Purchase');
            }
            DB::commit();
            session()->forget('confirmed_password');
            return to_route('purchase.index')->with('success', "Purchase Updated");
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
            $purchase = purchase::find($id);
            foreach($purchase->details as $product)
            {
                stock::where('refID', $product->refID)->delete();
                $product->delete();
            }
            transactions::where('refID', $purchase->refID)->delete();
            $purchase->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('purchase.index')->with('success', "Purchase Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('purchase.index')->with('error', $e->getMessage());
        }
    }

    public function getSignleProduct($id)
    {
        $product = products::find($id);
        return $product;
    }
}
