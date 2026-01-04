<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Exchange;

use Domain\Exchange\Requests\OrderExportRequest;
use Domain\Exchange\Resources\OrderResource;
use Domain\Order\Services\Exchange\OrderExchangeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Infrastructure\Http\Responses\ApiResponse;

class OrderController extends ExchangeController
{
    private Collection $orders;

    public function __construct()
    {
        $this->orders = new Collection();
    }

    public function getOrder(Request $request, OrderExchangeService $orderExchangeService): JsonResponse
    {
        $orderId = $request->get('id');

        $order = $orderExchangeService->getOrderById($orderId);

        return ApiResponse::handle(new OrderResource($order));
    }

    public function export(
        OrderExportRequest $request,
        OrderExchangeService $orderExchangeService,
    ): JsonResponse {
        $order1cIds = Arr::get($request->validated(), 'ids', []);

        $orders = $orderExchangeService->getOrdersForExport($order1cIds);

        Log::channel('exchange.order')->info(
            sprintf(
                '%s %s // %.2f',
                $request->method(),
                $_SERVER['REQUEST_URI'],
                microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']
            ),
        );

        return ApiResponse::handle(
            OrderResource::collection($orders),
        );
    }

    public function getStatus(
        Request $request,
        OrderExchangeService $orderExchangeService,
    ): JsonResponse {
        $orderIds = $request->get('orders');

        $data = $orderExchangeService->getOrdersStatus($orderIds);

        return ApiResponse::handle($data);
    }
}
