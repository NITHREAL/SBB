<?php

namespace App\Http\Controllers\Api\V1\PromoAction;

use App\Http\Controllers\Controller;
use Domain\PromoAction\DTO\PromoActionCollectionDTO;
use Domain\PromoAction\DTO\PromoActionOneDTO;
use Domain\PromoAction\DTO\PromoActionPage\PromoActionPageFiltersDTO;
use Domain\PromoAction\DTO\PromoActionPage\PromoActionPageParamsDTO;
use Domain\PromoAction\Requests\PromoActionOneRequest;
use Domain\PromoAction\Requests\PromoActionPage\PromoActionPageRequest;
use Domain\PromoAction\Requests\PromoActionsRequest;
use Domain\PromoAction\Resources\PromoActionOneResource;
use Domain\PromoAction\Resources\PromoActionPage\PromoActionPageResource;
use Domain\PromoAction\Resources\PromoActionResource;
use Domain\PromoAction\Services\PromoActionPage\PromoActionPageService;
use Domain\PromoAction\Services\PromoActionService;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Responses\ApiResponse;

class PromoActionController extends Controller
{
    public function index(
        PromoActionsRequest $request,
        PromoActionService $promoActionService,
    ): JsonResponse {
        $dto = PromoActionCollectionDTO::make($request->validated());

        $promoActions = $promoActionService->getPromoActions($dto);

        return ApiResponse::handle(
            PromoActionResource::collection($promoActions),
        );
    }

    public function show(
        string $slug,
        PromoActionOneRequest $request,
        PromoActionService $promoActionService,
    ): JsonResponse {
        $data = array_merge(
            $request->validated(),
            ['slug' => $slug],
        );

        $dto = PromoActionOneDTO::make($data);

        $promoAction = $promoActionService->getOnePromoAction($dto);

        return ApiResponse::handle(
            PromoActionOneResource::make($promoAction),
        );
    }

    public function catalog(
        string $slug,
        PromoActionPageRequest $request,
        PromoActionPageService $promoActionPageService,
    ): JsonResponse {
        $data = $request->validated();

        $paramsDTO = PromoActionPageParamsDTO::make($data, $slug);
        $filtersDTO = PromoActionPageFiltersDTO::make($data);

        $promoActionPageData = $promoActionPageService->getPromoActionPageData(
            $paramsDTO,
            $filtersDTO,
            json_encode($request->all()),
        );

        return ApiResponse::handle(
            PromoActionPageResource::make($promoActionPageData),
        );
    }
}
