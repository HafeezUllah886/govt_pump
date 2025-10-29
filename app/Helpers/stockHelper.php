<?php

use App\Models\stock;

function createStock($id, $cr, $db, $date, $notes, $ref, $warehouse_id)
{
    stock::create(
        [
            'product_id'     => $id,
            'cr'            => $cr,
            'db'            => $db,
            'date'          => $date,
            'notes'         => $notes,
            'refID'         => $ref,
            'warehouse_id'  => $warehouse_id
        ]
    );
}
function getStock($id){
   
        $cr  = stock::where('product_id', $id)->sum('cr');
        $db  = stock::where('product_id', $id)->sum('db');
  
    return $cr - $db;
}

function getWarehouseProductStock($id, $warehouse){
    $stocks  = stock::where('product_id', $id)->where('warehouse_id', $warehouse)->get();
    $balance = 0;
    foreach($stocks as $stock)
    {
        $balance += $stock->cr;
        $balance -= $stock->db;
    }

    return $balance;
}

