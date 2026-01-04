<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Domain\Basket\Exceptions\BasketProductException;
use Domain\Basket\Resources\BasketDataResource;
use Domain\Order\Exceptions\OrderException;
use Domain\Order\Services\OrderCancelService;
use Domain\Order\Services\OrderRepeatService;
use Domain\User\DTO\Order\UserClosestOrderDTO;
use Domain\User\DTO\Order\UserOrdersDTO;
use Domain\User\DTO\Order\UserOrdersHistoryDTO;
use Domain\User\Exceptions\ClosestOrderException;
use Domain\User\Models\User;
use Domain\User\Requests\Order\UserOrdersHistoryRequest;
use Domain\User\Requests\Order\UserOrdersRequest;
use Domain\User\Resources\Order\UserOrderResource;
use Domain\User\Resources\Order\UserOrdersResource;
use Domain\User\Services\Orders\UserOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;

class UserOrderController extends Controller
{
    public function index(
        UserOrdersRequest $request,
        UserOrderService $userOrderService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $userOrdersDTO = UserOrdersDTO::make($request->validated(), $user);

        return ApiResponse::handle(
            UserOrdersResource::make($userOrderService->getOrders($userOrdersDTO))
        );
    }

    public function history(
        UserOrdersHistoryRequest $request,
        UserOrderService $userOrderService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $userOrdersHistoryDTO = UserOrdersHistoryDTO::make($request->validated(), $user);

        return ApiResponse::handle(
            UserOrdersResource::make($userOrderService->getAllOrders($userOrdersHistoryDTO))
        );
    }

    /**
     * @throws ClosestOrderException
     */
    public function closest(
        UserOrderService $userOrderService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $userClosestOrderDTO = UserClosestOrderDTO::make($user);

        $order = $userOrderService->getUserClosestOrder($userClosestOrderDTO);

        return ApiResponse::handle(
            UserOrderResource::make($order),
        );
    }

    public function show(
        int $id,
        UserOrderService $userOrderService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        return ApiResponse::handle(
            UserOrderResource::make($userOrderService->getOrder($id, $user)),
        );
    }

    /**
     * @throws BasketProductException
     */
    public function repeat(
        int $id,
        OrderRepeatService $orderService,
    ): JsonResponse {
        return ApiResponse::handle(
            BasketDataResource::make(
                $orderService->repeatOrder($id),
            )
        );
    }

    /**
     * @throws OrderException
     */
    public function cancel(
        int $id,
        OrderCancelService $orderCancelService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        return ApiResponse::handle(
            UserOrderResource::make($orderCancelService->cancelOrder($id, $user)),
        );
    }
}
