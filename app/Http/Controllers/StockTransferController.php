<?php

namespace App\Http\Controllers;

use App\Models\StockTransfer;
use App\Http\Controllers\Controller;
use App\Models\accounts;
use App\Models\product_units;
use App\Models\products;
use App\Models\RouteExpenses;
use App\Models\transactions;
use App\Models\stock;
use App\Models\StockTransferDetails;
use App\Models\warehouses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->start ?? firstDayOfMonth();
        $to = $request->end ?? date('Y-m-d');
        $stockTransfers = StockTransfer::whereBetween('date', [$from, $to])->get();
        $warehouses = warehouses::all();
       
        return view('stock.transfer.index', compact('stockTransfers', 'warehouses', 'from', 'to'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if($request->fromWarehouse == $request->toWarehouse){
            return redirect()->back()->with('error', 'From and To Warehouse cannot be the same');
        }
            $accounts = accounts::business()->get();
            $warehouseFrom = warehouses::find($request->fromWarehouse);
            $warehouseTo = warehouses::find($request->toWarehouse);
            $products = products::all();
            $transporters = accounts::transporter()->get();
            foreach($products as $product){
               $product->stock = getWarehouseProductStock($product->id, $warehouseFrom->id);
            }

        return view('stock.transfer.create', compact('products', 'warehouseFrom', 'warehouseTo', 'accounts', 'transporters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $ref = getRef();
            $stockTransfer = StockTransfer::create([
                'warehouse_from_id' => $request->from,
                'warehouse_to_id' => $request->to,
                'product_id' => $request->product,
                'qty' => $request->qty,
                'expense' => $request->expense,
                'transporter_id' => $request->transporter,
                'status' => $request->status,
                'account_id' => $request->account,
                'date' => $request->date,
                'notes' => $request->notes,
                'refID' => $ref,
                'user_id' => auth()->user()->id,
            ]);

            $warehouseFrom = warehouses::find($request->from);
            $warehouseTo = warehouses::find($request->to);

            createStock($request->product, 0, $request->qty, $request->date, "Transfered to $warehouseTo->name:  $request->notes", $ref, $request->from);
            createStock($request->product, $request->qty, 0, $request->date, "Transfered from $warehouseFrom->name:  $request->notes", $ref, $request->to);

            RouteExpenses::create([
                'date' => $request->date,
                'transporter_id' => $request->transporter,
                'amount' => $request->expense,
                'notes' => $request->notes,
                'refID' => $ref,
            ]);

            if($request->status == 'Paid'){
                $notes = "Received for Stock Transfer Expense : Notes: ".$request->notes;
                createTransaction($request->transporter, $request->date, $request->expense, $request->expense, $notes, $ref);
                $transporter = accounts::find($request->transporter)->title;
                $notes = "Paid for Stock Transfer Expense to $transporter : Notes: ".$request->notes;
                createTransaction($request->account, $request->date, 0, $request->expense, $notes, $ref);
            }
            else
            {
                $notes = "Pending amount for Stock Transfer Expense : Notes: ".$request->notes;
                createTransaction($request->transporter, $request->date, 0, $request->expense, $notes, $ref);
            }
            DB::commit();
            return redirect()->route('stockTransfers.index')->with('success', 'Stock Transfer Created Successfully');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $stockTransfer = StockTransfer::with('details')->find($id);
        return view('stock.transfer.details', compact('stockTransfer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockTransfer $stockTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockTransfer $stockTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ref)
    {
        try
        {
            DB::beginTransaction();
            StockTransfer::where('refID', $ref)->delete();
            stock::where('refID', $ref)->delete();
            RouteExpenses::where('refID', $ref)->delete();
            transactions::where('refID', $ref)->delete();
            DB::commit();
            session()->forget('confirmed_password');
            return redirect()->route('stockTransfers.index')->with('success', "Stock Transfer Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return redirect()->route('stockTransfers.index')->with('error', $e->getMessage());
        }
    }
}
