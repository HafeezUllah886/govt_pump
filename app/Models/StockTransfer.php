<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{

    use HasFactory;

    protected $guarded = [];

    public function fromWarehouse(){
        return $this->belongsTo(warehouses::class, 'warehouse_from_id');
    }

    public function toWarehouse(){
        return $this->belongsTo(warehouses::class, 'warehouse_to_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transporter()
    {
        return $this->belongsTo(accounts::class, 'transporter_id');
    }
    public function account()
    {
        return $this->belongsTo(accounts::class, 'account_id');
    }
    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }
   
    
}
