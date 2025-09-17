<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class trans_laundry_pickup extends Model
{
    protected $fillable = [
        'id_order',
        'id_customer',
        'pickup_date',
        'notes'
    ];
}
