<?php

namespace Domain\Order\Services\Exchange;

use Domain\Exchange\Resources\OrderResource;
use Domain\Exchange\Services\OneC\OneCService;
use Domain\Order\Enums\Exchange\OrderExchangeTypeEnum;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

readonly class OrderExchangeService
{
    public function __construct(
        private OrderExchangeLogService $exchangeLogService,
        private OneCService $oneCService
    ) {
    }

    public function getOrderById(string $orderId): Order
    {
        return Order::findOrFail($orderId);
    }

    //TODO оптимизировать логику
    public function getOrdersForExport(array $order1cIds): Collection
    {
        $query = Order::query();

        if (count($order1cIds)) {
            $query->whereIn('id', $order1cIds);
        } else {
            $query
                ->whereNeedExchange()
                ->where('orders.status', '!=', OrderStatusEnum::surcharge()->value)
                ->whereHas('store', function (Builder $query) {
                    $query->where('stores.is_dark_store', false);
                })
                ->where(function (Builder $query) {
                    $query
                        ->where('orders.status', '!=', OrderStatusEnum::waitingPayment()->value)
                        ->orWhereDate('orders.created_at', '<', Carbon::now()->addHour());
                })
                ->take(25);
        }

        $orders = $query
            ->with([
                'products',
                'contacts',
                'user' => fn($query) => $query->withTrashed(),
                'promocode',
                'payments.logs' => fn($query) => $query->whereIn('method', ['getOrderStatus', 'fixRRN']),
                'utm',
            ])
            ->orderBy('orders.id')
            ->get();

        $orders->map(function ($order) {
            $this->exchangeLogService->logOrderExchange($order, OrderExchangeTypeEnum::export()->value);
        });

        return $orders;
    }

    public function getOrdersStatus(array $orderIds): array
    {
        return Order::query()
            ->select(['orders.id', 'orders.status'])
            ->whereIn('id', $orderIds)
            ->get()
            ->toArray();
    }

    public function exportOrderTo1C(Order $order): void
    {
        $this->sendOrderDataTo1C($order);

        $this->exchangeLogService->logOrderExchange($order, OrderExchangeTypeEnum::export()->value);
    }

    private function sendOrderDataTo1C(Order $order): void
    {
        $orderData = (new OrderResource($order));

        $response = $this->oneCService->sendData(
            json_decode($orderData->toJson(), true)
        );

        Log::channel('exchange.onec')->info("System ID: " . Arr::get($response, 'data.system_id'));

        $order->need_exchange = false;

        if (Arr::has($response, 'data.system_id')) {
            $systemId = Arr::get($response, 'data.system_id');
            $order->system_id = $systemId;
            Log::channel('exchange.onec')->info("System ID saved to order №{$order->id}, system_id: {$systemId}");
        }

        $order->save();
    }
}
