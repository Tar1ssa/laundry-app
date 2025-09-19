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

    public function order()
    {
        return $this->belongsTo(Trans_order::class, 'id_order', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Type_of_service::class, 'id_service', 'id');
    }
}
