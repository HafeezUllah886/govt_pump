<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteExpenses extends Model
{
    protected $guarded = [];

    public function transporter()
    {
        return $this->belongsTo(accounts::class, 'transporter_id');
    }
    public function account()
    {
        return $this->belongsTo(accounts::class, 'account_id');
    }
  

}
