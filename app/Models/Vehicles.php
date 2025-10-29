<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(accounts::class, 'department_id', 'id');
    }
}
