<?php

namespace App\Repositories;

use App\Models\Trans_order;
use App\Models\Trans_order_detail;

class EloquentTransactionRepository implements TransactionRepositoryInterface
{
    public function createOrder(array $data)
    {
        return Trans_order::create($data);
    }

    public function createOrderDetail(array $data)
    {
        return Trans_order_detail::create($data);
    }
}
