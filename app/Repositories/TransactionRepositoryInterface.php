<?php

namespace App\Repositories;

interface TransactionRepositoryInterface
{
    public function createOrder(array $data);
    public function createOrderDetail(array $data);
}
