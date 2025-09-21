<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Trans_order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id_customer',
        'order_code',
        'order_date',
        'order_end_date',
        'order_status',
        'order_pay',
        'order_change',
        'total'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id');
    }

    public function detailOrder()
    {
        return $this->hasMany(Trans_order_detail::class, 'id_order', 'id');
    }

    protected $date = ['deleted_at'];
}
