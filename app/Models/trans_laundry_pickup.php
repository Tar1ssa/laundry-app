<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trans_laundry_pickup extends Model
{
    protected $fillable = [
        'id_order',
        'id_customer',
        'pickup_date',
        'notes'
    ];

    public function customerName()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Trans_order::class, 'id_order', "id");
    }
}
