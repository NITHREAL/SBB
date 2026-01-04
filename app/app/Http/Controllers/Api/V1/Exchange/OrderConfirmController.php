<?php

namespace App\Http\Controllers\Api\V1\Exchange;

use Domain\Exchange\Requests\OrderSyncCollectionRequest;
use Domain\Exchange\Requests\OrderSyncItemRequest;
use Domain\Exchange\Resources\ResultResource;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class OrderConfirmController extends ExchangeController
{
    public function exchangeCollection(OrderSyncCollectionRequest $request): ResultResource
    {
        Log::channel('exchange.order_confirm')->info('Processing order confirm collection exchange', $request->all());
        return $this->doExchange($request);
    }

    public function exchange(OrderSyncItemRequest $request): Model
    {
        $data = $request->validated();

        Log::channel('exchange.order_confirm')->info('Processing order confirm item exchange', $data);
        $order = Order::with('exchangeLastLog')->findOrFail($data['id']);

        if (!$order->getAttribute('system_id')) {
            $order->setAttribute('status', OrderStatusEnum::accepted()->value);
        }

        $order->setAttribute('system_id', $data['system_id']);

        $lastLog = $order->exchangeLastLog;

        if ($lastLog && $lastLog->created_at > $order->updated_at) {
            $order->need_exchange = false;
        }

        $order->save();

        return $order;
    }
}
