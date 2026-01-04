<?php

namespace App\Http\Controllers\Api\V1\Order\Sberbank;

use Domain\Order\DTO\Sberbank\SberbankNotificationDTO;
use Domain\Order\Services\Sberbank\SberbankNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Infrastructure\Http\Responses\ApiResponse;

class SberbankController
{
    public function index(
        Request $request,
        SberbankNotificationService $sberbankNotificationService,
    ): JsonResponse {
        Log::channel('payment')->info(
            sprintf(
                'Получено уведолмение от сбербанка. Тело уведомления: [%s]',
                json_encode($request->all()),
            ),
        );

        $sberbankNotificationDTO = SberbankNotificationDTO::make($request->all());

        $sberbankNotificationService->processSberbankNotification($sberbankNotificationDTO);

        return ApiResponse::handle([]);
    }
}
