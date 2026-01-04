<?php

namespace Domain\Order\Resources\Order;

use Domain\Order\Helpers\Delivery\OrderDeliveryHelper;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Services\Delivery\DateServices\ReceiveInterval;
use Domain\ProductGroup\Resources\ProductGroupResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        $order = $this->resource;
        $products = $order->products;
        $contacts = $order->contacts;

        return [
            'id'                => (int)$order->id,
            'systemId'          => OrderHelper::makeSystemId($order->id),
            'status'            => $order->status,
            'bindingId'         => $order->binding_id,
            'canCancel'         => false, // TODO добавить логику
            'paymentType'       => $order->payment_type,
            'deliveryType'      => $order->delivery_type,
            'deliverySubType'   => $order->delivery_sub_type,
            'receiveDate'       => $order->receive_date ?? $order->completed_at,
            'receiveInterval'   => ReceiveInterval::labelFromString($order->receive_interval),
            'productsCount'     => $products->count(),
            'productsTotal'     => $order->total_price,
            'products'          => ProductGroupResource::collection($products),
            'needPayment'       => OrderHelper::isPaymentNeed($order),
            'promo'             => null,
            'address'           => OrderDeliveryHelper::isDelivery($order->delivery_type)
                ? $contacts?->address
                : $order->store->address,
            'sberUrl'           => $order->pay_url,
            'settings'          => OrderSettingsResource::make($order),
        ];
    }
}
