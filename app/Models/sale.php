<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sale extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(accounts::class, 'department_id');
    }
    public function Vehicle()
    {
        return $this->belongsTo(Vehicles::class, 'vehicle_id');
    }

    public function details()
    {
        return $this->hasMany(sale_details::class, 'sale_id');
    }

  
}
