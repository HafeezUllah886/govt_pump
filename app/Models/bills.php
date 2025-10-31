<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class bills extends Model
{
    protected $guarded = [];
    
      public function department()
    {
        return $this->belongsTo(accounts::class, 'department_id');
    }
    public function Vehicle()
    {
        return $this->belongsTo(Vehicles::class, 'vehicle_id');
    }

}
