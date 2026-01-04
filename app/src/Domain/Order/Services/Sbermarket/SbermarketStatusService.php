<?php

namespace Domain\Order\Services\Sbermarket;

use Domain\Order\Models\Order;
use Illuminate\Http\Client\HttpClientException;

class SbermarketStatusService
{
    public function __construct(
        private readonly SbermarketOrderProductsService $orderProductsService,
        private readonly SbermarketCurlService $curlService,
    ) {
    }

    /**
     * Обновить статус
     *
     * @param  Order  $order
     * @param  string  $status
     * @return bool
     * @throws HttpClientException
     */
    public function changeStatus(Order $order, string $status): bool
    {
        //Статус нужно отправить, только если он изменился
        if ($status === $order->sm_status) {
            return false;
        }

        $data["event"] = $this->getPreparedEventData($order);

        $this->handleReadyForDeliveryStatus($order, $status, $data);

        $result = $this->curlService->send($data, $status);

        if ($result) {
            $order->updateQuietly(['sm_status' => $status]);
        }

        return $result;
    }

    private function getPreparedEventData(Order $order): array
    {
        $positions = $this->orderProductsService->getPreparedOrderProducts($order);

        return [
            "payload" => [
                "order_id"  => $order->sm_original_order_id,
                "order"     => [
                    "originalOrderId"   => $order->sm_original_order_id,
                    "customer"          => [
                        "name"  => $order->user->first_name . ' ' . $order->user->last_name,
                        "phone" => $order->user->phone,
                    ],
                    "positions"         => $positions,
                    "total"             => [
                        "totalPrice"            => (string) $order->totalWithoutDiscount,
                        "discountTotalPrice"    => (string) $order->total,
                    ],
                ],
            ],
        ];
    }

    /**
     * @throws HttpClientException
     */
    private function handleReadyForDeliveryStatus(
        Order $order,
        string $status,
        array $data,
    ): void {
        // Этот костыль сделан чтобы выстроить флоу,
        // согласно которому перед статусом ready_for_delivery, должен быть in_work

        if ($status === 'order.ready_for_delivery' && empty($order->sm_status)) {
            $result = $this->curlService->send($data, 'order.in_work');

            if ($result) {
                $order->updateQuietly(['sm_status' => 'order.in_work']);
            }
        }
    }
}
