<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type_of_service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_name',
        'price',
        'description',
    ];

    public function orderDetails()
    {
        return $this->hasMany(Trans_order_detail::class, 'id_service', 'id');
    }

    protected $date = ['deleted_at'];
}
