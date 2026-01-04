<?php

namespace App\Http\Controllers\Api\V1\Order;

use Domain\Order\Resources\Payment\OnlinePaymentBindingInitResource;
use Domain\Order\Resources\Payment\OnlinePaymentBindingResource;
use Domain\Order\Services\Payment\Exceptions\RegisterPaymentDoException;
use Domain\Order\Services\Payment\OnlinePaymentBindingRequestService;
use Domain\Order\Services\Payment\PaymentBindingService;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;

class OnlinePaymentBindingController
{
    public function index(PaymentBindingService $paymentBindingService): JsonResponse
    {
        $paymentBindings = $paymentBindingService->getUserBindings(Auth::id());

        return ApiResponse::handle(
            OnlinePaymentBindingResource::collection($paymentBindings),
        );
    }

    /**
     * @throws RegisterPaymentDoException
     */
    public function store(
        OnlinePaymentBindingRequestService $paymentBindingRequestService
    ): JsonResponse {
        /* @var User $user */
        $user = Auth::user();

        $paymentBindingRequestData = $paymentBindingRequestService->processPaymentBindingRequest($user);

        return ApiResponse::handle(
            OnlinePaymentBindingInitResource::make($paymentBindingRequestData),
        );
    }

    public function destroy(
        int $id,
        PaymentBindingService $paymentBindingService,
    ): JsonResponse {
        $paymentBindingService->removeBinding($id);

        return ApiResponse::handleNoContent();
    }
}
