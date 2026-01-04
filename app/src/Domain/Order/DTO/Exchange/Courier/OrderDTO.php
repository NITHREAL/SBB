<?php

namespace Domain\Order\DTO\Exchange\Courier;

use Domain\Order\Models\Order;
use Domain\Product\Models\Product;
use Infrastructure\DTO\BaseDTO;

class OrderDTO extends BaseDTO
{
    public function __construct(
        public int $orderId,
        public ?string $systemId,
        public ?string $uuid,
        public ?string $storeSystemId,
        public ?string $status,
        public ?string $deliveryType,
        public ?string $replacementPolicy,
        public ?string $comment,
        public ?string $receiveDate,
        public ?string $receiveInterval,
        public ?string $totalPrice,
        public ?string $discount,
        public array   $contacts,
        public array   $products,
    ) {
    }

    public static function fromModel(Order $order): self
    {
        return new self(
            orderId: $order->id,
            systemId: $order->system_id,
            uuid: $order->uuid,
            storeSystemId: $order->store_system_id,
            status: (string)$order->status,
            deliveryType: $order->delivery_type,
            replacementPolicy: $order->replacement_type,
            comment: $order->comment,
            receiveDate: $order->receive_date,
            receiveInterval: $order->receive_interval,
            totalPrice: $order->total_price,
            discount: $order->discount,
            contacts: self::formatContacts($order),
            products: self::formatProducts($order),
        );
    }

    private static function formatProducts(Order $order): array
    {
        return $order->products()->get()->map(function (Product $product) {
            return [
                'systemId' => $product->system_id,
                'unitSystemId' => $product->pivot->unit_system_id,
                'replacementSystemId' => $product->pivot->replacement_system_id,
                'status' => $product->pivot->status,
                'price' => $product->pivot->price,
                'priceDiscount' => $product->pivot->price_discount,
                'count' => $product->pivot->count,
                'originalQuantity' => $product->pivot->original_quantity,
                'collectedQuantity' => $product->pivot->collected_quantity,
                'weight' => $product->pivot->weight,
                'total' => $product->pivot->total,
                'totalWithoutDiscount' => $product->pivot->total_without_discount,
                'barcodes'  => $product->barcodes,
            ];
        })->toArray();
    }

    private static function formatContacts(Order $order): array
    {
        return $order->contacts()->first()->toArray();
    }
}
