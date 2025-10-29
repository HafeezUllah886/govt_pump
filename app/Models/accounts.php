<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class accounts extends Model
{
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }


   
}
