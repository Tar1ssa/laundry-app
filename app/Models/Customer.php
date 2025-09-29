<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_name',
        'phone',
        'address',
    ];

    public function transaction()
    {
        return $this->hasMany(Trans_order::class, 'id_customer', 'id');
    }

    protected $date = ['deleted_at'];
}
