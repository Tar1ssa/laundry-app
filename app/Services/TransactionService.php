<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Repositories\TransactionRepositoryInterface;
use Throwable;

class TransactionService
{
    protected $repo;

    public function __construct(TransactionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Create order with details.
     * $payload expects keys:
     *  - id_customer, order_code, order_date, order_pay, order_change, total, id_service[], qty[], subtotali[], note[]
     */
    public function createTransaction(array $payload)
    {
        DB::beginTransaction();

        try {
            // Prepare order data
            $orderData = [
                'id_customer' => $payload['id_customer'],
                'order_code' => $payload['trans_code'] ?? $payload['order_code'] ?? null,
                'order_date' => $payload['order_date'],
                'order_pay' => $payload['order_pay'] ?? 0,
                'order_change' => $payload['order_change'] ?? 0,
                'total' => $payload['total'] ?? 0,
            ];

            if (isset($payload['order_pay']) && floatval($payload['order_pay']) >= floatval($orderData['total'])) {
                $orderData['order_end_date'] = Carbon::now();
            }

            $order = $this->repo->createOrder($orderData);

            // Create details
            $services = $payload['id_service'] ?? [];
            foreach ($services as $index => $serviceId) {
                $detail = [
                    'id_order' => $order->id,
                    'id_service' => $serviceId,
                    'qty' => $payload['qty'][$index] ?? 1,
                    'subtotal' => $payload['subtotali'][$index] ?? 0,
                    'notes' => $payload['note'][$index] ?? null
                ];
                $this->repo->createOrderDetail($detail);
            }

            DB::commit();

            return $order;
        } catch (Throwable $e) {
            DB::rollBack();
            // biarkan controller atau caller menangani exception lebih lanjut
            throw $e;
        }
    }
}
