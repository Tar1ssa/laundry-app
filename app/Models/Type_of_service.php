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

    protected $date = ['deleted_at'];
}
