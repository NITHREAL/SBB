<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Exchange\Order;

use Domain\Exchange\Requests\ItemRequest;
use Domain\Exchange\Requests\OrderCollectionRequest;
use Domain\Exchange\Requests\OrderItemRequest;
use Domain\Order\DTO\Exchange\OrderExchangeDTO;
use Domain\Order\DTO\Exchange\OrderUpdateExchangeDTO;
use Domain\Order\Jobs\Sbermarket\CheckSbermarketOrdersJob;
use Domain\Order\Models\Order;
use Domain\Order\Services\Exchange\OrderExchangeLogService;
use Domain\Order\Services\Exchange\OrderExchangeUpdateService;
use Domain\Exchange\Resources\ResultResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class OrderUpdateController extends BaseOrderController
{
    private Collection $orders;

    private readonly OrderExchangeUpdateService $orderExchangeUpdateService;

    public function __construct()
    {
        $this->orders = new Collection();
        $this->orderExchangeUpdateService  = app()->make(OrderExchangeUpdateService::class);
    }

    public function exchangeCollection(
        OrderCollectionRequest $request,
        OrderExchangeLogService $orderExchangeLogService,
    ): ResultResource {
        $request->map(fn(ItemRequest $item) => $orderExchangeLogService->logOrderExchangeRequest($item->all()));

        Log::channel('exchange.order')->info('Processing order updating collection exchange', $request->all());

        $response = $this->doExchange($request);

        CheckSbermarketOrdersJob::dispatch($this->orders);

        return $response;
    }

    /**
     * @throws \Exception
     */
    public function exchange(OrderItemRequest $request): Order
    {
        $data = $request->validated();
        $orderDTO = OrderUpdateExchangeDTO::make($data);

        $order = $this->orderExchangeUpdateService->updateOrder($orderDTO);

        $this->orders->add($order);

        return $order;
    }
}
