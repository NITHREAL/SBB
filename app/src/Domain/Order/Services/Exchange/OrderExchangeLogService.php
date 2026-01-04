<?php

namespace Domain\Order\Services\Exchange;


use Domain\Order\Models\Exchange\OrderExchangeLog;
use Domain\Order\Models\Exchange\OrderExchangeRequestLog;
use Domain\Order\Models\Order;
use Domain\Product\Models\Product;
use Illuminate\Support\Arr;

class OrderExchangeLogService
{
    public function logOrderExchange(Order $order, string $exchangeType): void
    {
        $order->load('promocode', 'products');

        $orderLog = new OrderExchangeLog();

        $data = $order->getAttributes();

        $data['promo'] = $order->promocode?->toArray();
        $data['products'] = $order->products
            ?->map(function (Product $product) {
                return [
                    'id'                     => $product->id,
                    'system_id'              => $product->getAttribute('system_id'),
                    'title'                  => $product->title,
                    'unit_system_id'         => $product->pivot->unit_system_id,
                    'price'                  => $product->pivot->price,
                    'price_discount'         => $product->pivot->price_discount,
                    'price_promo'            => $product->pivot->price_promo,
                    'price_buy'              => $product->pivot->price_buy,
                    'is_discount'            => $product->pivot->is_discount,
                    'count'                  => $product->pivot->count,
                    'weight'                 => $product->pivot->weight,
                    'total'                  => $product->pivot->total,
                    'total_without_discount' => $product->pivot->total_without_discount,
                ];
            })
            ->toArray();

        $orderLog->fill([
            'type'      => $exchangeType,
            'status'    => $order->status,
            'data'      => json_encode($data),
        ]);

        $orderLog->order()->associate($order);

        $orderLog->save();
    }

    public function logOrderExchangeRequest(array $data): void
    {
        $exchangeRequestLog = new OrderExchangeRequestLog();

        $dataForLog = Arr::except($data, ['system_id', 'status']);

        $exchangeRequestLog->fill([
            'system_id'  => Arr::get($data, 'system_id'),
            'status' => Arr::get($data, 'status'),
            'data'   => json_encode($dataForLog),
        ]);

        $exchangeRequestLog->save();
    }
}
