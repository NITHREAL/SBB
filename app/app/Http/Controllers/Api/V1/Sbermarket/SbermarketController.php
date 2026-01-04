<?php

namespace App\Http\Controllers\Api\V1\Sbermarket;

use App\Http\Controllers\Controller;
use Domain\Order\DTO\Sbermarket\SbermarketOrderDTO;
use Domain\Order\DTO\Sbermarket\UpdateSbermarketPaymentDTO;
use Domain\Order\Exceptions\SbermarketException;
use Domain\Order\Requests\Sbermarket\SbermarketWebhookRequest;
use Domain\Order\Resources\Sbermarket\SbermarketOrderResource;
use Domain\Order\Resources\Sbermarket\SbermarketPaymentResource;
use Domain\Order\Services\Payment\Exceptions\PaymentException;
use Domain\Order\Services\Sbermarket\SbermarketOrderService;
use Domain\Order\Services\Sbermarket\SbermarketPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Infrastructure\Http\Responses\ApiResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SbermarketController extends Controller
{
    /**
     * @throws SbermarketException
     */
    public function index(
        SbermarketWebhookRequest $request,
        SbermarketOrderService $sbermarketOrderService,
    ): JsonResponse {
        Log::channel('sbermarket')->debug(PHP_EOL . json_encode($request->all()) . PHP_EOL);

        $sbermarketOrderDTO = SbermarketOrderDTO::make($request->validated());

        $result = $sbermarketOrderService->handleSbermarketOrder($sbermarketOrderDTO);

        return ApiResponse::handle(
            SbermarketOrderResource::make($result),
        );
    }

    public function handlePayment(
        Request $request,
        SbermarketPaymentService $sbermarketPaymentService,
    ): void {
        $sbermarketPaymentDTO = UpdateSbermarketPaymentDTO::make(
            $request->all(),
            $request->except('checksum', 'sign_alias'),
        );

        $result = $sbermarketPaymentService->updateSbermarketPayment($sbermarketPaymentDTO);

        $code = $result ? ResponseAlias::HTTP_OK : ResponseAlias::HTTP_BAD_REQUEST;

        abort($code);
    }

    /**
     * @throws PaymentException
     */
    public function checkStatus(
        Request $request,
        SbermarketPaymentService $sbermarketPaymentService,
    ): JsonResponse {
        $payment = $sbermarketPaymentService->checkSbermarketPaymentStatus(
            $request->get('uuid'),
            $request->get('orderId')
        );

        return ApiResponse::handle(
            SbermarketPaymentResource::make($payment),
        );
    }
}
