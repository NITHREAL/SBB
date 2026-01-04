<?php

namespace App\Http\Controllers\Api\V1\Order\Yookassa;

use Domain\Order\DTO\Yookassa\YookassaNotificationDTO;
use Domain\Order\Services\Yookassa\YookassaNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infrastructure\Http\Responses\ApiResponse;

class YookassaController
{
    public function index(
        Request $request,
        YookassaNotificationService $yookassaNotificationService,
    ): JsonResponse {
        $yookassaNotificationDTO = YookassaNotificationDTO::make($request->all());

        $yookassaNotificationService->processYookassaNotification($yookassaNotificationDTO);

        return ApiResponse::handle([]);
    }
}
