<?php

namespace App\Http\Controllers\Api\V1\Order;

use App\Http\Controllers\Controller;
use Domain\Order\DTO\Review\OrderReviewDTO;
use Domain\Order\Exceptions\OrderException;
use Domain\Order\Requests\Review\OrderReviewRequest;
use Domain\Order\Resources\Review\OrderReviewResource;
use Domain\Order\Services\Review\OrderReviewService;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;

class OrderReviewController extends Controller
{
    /**
     * @throws OrderException
     */
    public function store(
        int $id,
        OrderReviewRequest $request,
        OrderReviewService $orderReviewService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $orderReviewDTO = OrderReviewDTO::make($request->validated(), $id, $user);

        return ApiResponse::handle(
            OrderReviewResource::make($orderReviewService->create($orderReviewDTO)),
        );
    }
}
