<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trans_order_detail extends Model
{
    protected $fillable = [
        'id_order',
        'id_service',
        'qty',
        'subtotal',
        'notes'
    ];
}
