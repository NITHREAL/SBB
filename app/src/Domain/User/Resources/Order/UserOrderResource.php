<?php

namespace Domain\User\Resources\Order;

use Domain\Order\Enums\Payment\PaymentTypeEnum;
use Domain\Order\Helpers\OrderHelper;
use Domain\Order\Services\Delivery\DateServices\ReceiveInterval;
use Domain\Product\Helpers\ProductHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserOrderResource extends JsonResource
{
    public function toArray($request): array
    {
        $order = $this->resource;
        $products = $order->products;

        $isPaymentNeed = OrderHelper::isPaymentNeed($order);
        $paymentType = $order->payment_type;

        return [
            'id'                => (int)$order->id,
            'systemId'          => OrderHelper::makeSystemId($order->id),
            'status'            => $order->status,
            'paymentType'       => $paymentType,
            'paymentTypeLabel'  => Arr::get(PaymentTypeEnum::toArray(), $paymentType),
            'deliveryType'      => $order->delivery_type,
            'deliverySubType'   => $order->delivery_sub_type,
            'date'              => $order->receive_date,
            'time'              => ReceiveInterval::labelFromString($order->receive_interval),
            'productsCount'     => $products->count(),
            'productsTotal'     => ProductHelper::getPreparedProductPrice($order->productsTotal),
            'total'             => ProductHelper::getPreparedProductPrice($order->total),
            'deliveryCost'      => ProductHelper::getPreparedProductPrice($order->deliveryCost),
            'discount'          => ProductHelper::getPreparedProductPrice($order->discount),
            'reviewAvailable'   => $order->reviewAvailable,
            'electronicCheck'   => $order->need_receipt,
            'rate'              => $order->rate,
            'needPayment'       => $isPaymentNeed,
            'sberUrl'           => $isPaymentNeed ? $order->pay_url : null,
            'canCancel'         => OrderHelper::isCancellationAvailable($order),
            'canRepeat'         => $order->isRepeatable,
            'isSupportable'     => $order->isSupportable,
            'address'           => $order->address,
            'products'          => UserOrderProductResource::collection($products),
            'comment'           => $order->comment,
            'completedAt'       => $order->completed_at?->format('Y-m-d H:i'),
            'updatedAt'         => $order->updated_at->format('Y-m-d H:i'),
        ];
    }
}
