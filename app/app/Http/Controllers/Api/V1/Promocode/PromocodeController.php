<?php

namespace App\Http\Controllers\Api\V1\Promocode;

use App\Http\Controllers\Controller;
use Domain\Promocode\DTO\GetFirstOrderPromocodeDTO;
use Domain\Promocode\DTO\GetPromocodesDTO;
use Domain\Promocode\Requests\GetPromocodesRequest;
use Domain\Promocode\Resources\PromocodeResource;
use Domain\Promocode\Service\PromocodeSelectionService;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;

class PromocodeController extends Controller
{
    public function index(
        GetPromocodesRequest $request,
        PromocodeSelectionService $promocodeSelectionService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $promocodesDTO = GetPromocodesDTO::make($request->validated(), $user);

        return ApiResponse::handle(
            PromocodeResource::collection($promocodeSelectionService->getPromocodes($promocodesDTO))
        );
    }

    public function firstOrder(
        GetPromocodesRequest $request,
        PromocodeSelectionService $promocodeSelectionService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $promocodesDTO = GetFirstOrderPromocodeDTO::make($request->validated(), $user);

        return ApiResponse::handle(
            PromocodeResource::make($promocodeSelectionService->getFirstOrderPromocode($promocodesDTO))
        );
    }
}
