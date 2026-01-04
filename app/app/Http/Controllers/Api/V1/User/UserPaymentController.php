<?php

namespace App\Http\Controllers\Api\V1\User;

use Domain\User\Models\User;
use Domain\User\Resources\Payment\UserPaymentMethodsResource;
use Domain\User\Services\Payment\UserPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;

class UserPaymentController
{
    public function paymentMethods(UserPaymentService $userPaymentService): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        return ApiResponse::handle(
            UserPaymentMethodsResource::make(
                $userPaymentService->getPaymentMethods($user),
            ),
        );
    }
}
