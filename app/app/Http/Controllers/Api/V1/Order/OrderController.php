<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Order;

use App\Http\Controllers\Controller;
use Domain\Order\DTO\OrderDTO;
use Domain\Order\Exceptions\OrderException;
use Domain\Order\Requests\CreateOrderRequest;
use Domain\Order\Resources\Order\OrderResource;
use Domain\Order\Services\Delivery\Exceptions\DeliveryTypeException;
use Domain\Order\Services\OrderService;
use Domain\Order\Services\OrderTestService;
use Domain\Order\Services\Payment\Exceptions\PaymentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infrastructure\Http\Responses\ApiResponse;

class OrderController extends Controller
{
    /**
     * @throws OrderException
     * @throws DeliveryTypeException
     */
    public function create(
        CreateOrderRequest $request,
        OrderService $orderService,
    ): JsonResponse {
        $orderDTO = OrderDTO::make(
            array_merge(
                $request->validated(),
                ['payerIp' => $request->ip()],
            )
        );

        $orders = $orderService->createOrders($orderDTO);

        return ApiResponse::handle(
            OrderResource::collection($orders),
        );
    }

    /**
     * Для теста поведения при смене статуса заказа на "Собран"
     *
     * @param Request $request
     * @param OrderTestService $orderTestService
     * @return JsonResponse
     */
    public function collect(
        Request $request,
        OrderTestService $orderTestService,
    ): JsonResponse {
        $orderTestService->makeOrderCollected(
            (int) $request->get('orderId'),
            (int) $request->get('amount'),
        );

        return ApiResponse::handleNoContent();
    }

    /**
     * Для теста поведения при смена статуса заказа на "Завершен"
     *
     * @param Request $request
     * @param OrderTestService $orderTestService
     * @return JsonResponse
     */
    public function complete(
        Request $request,
        OrderTestService $orderTestService,
    ): JsonResponse {
        $orderTestService->makeOrderCompleted((int) $request->get('orderId'));

        return ApiResponse::handleNoContent();
    }

    // Тестовый метод для первичного платеже

    /**
     * @throws PaymentException
     * @throws OrderException
     */
    public function initPayment(
        Request $request,
        OrderTestService $orderTestService,
    ): JsonResponse {
        $orderTestService->checkOrderInitPayment((int) $request->get('orderId'));

        return ApiResponse::handleNoContent();
    }
}
