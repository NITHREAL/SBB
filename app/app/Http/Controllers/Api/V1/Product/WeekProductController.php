<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use Domain\Product\DTO\Product\WeekProductFiltersDTO;
use Domain\Product\DTO\Product\WeekProductParamsDTO;
use Domain\Product\Requests\WeekProduct\WeekProductRequest;
use Domain\Product\Resources\Catalog\WeekProductCatalogResource;
use Domain\Product\Resources\Catalog\WeekProductResource;
use Domain\Product\Services\WeekProduct\WeekProductCatalogService;
use Domain\Product\Services\WeekProduct\WeekProductSelectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Infrastructure\Http\Responses\ApiResponse;

class WeekProductController extends Controller
{
    public function index(
        WeekProductRequest $request,
        WeekProductSelectionService $weekProductSelectionService,
    ): JsonResponse {
        $limit = Arr::get($request->validated(), 'limit');

        $weekProduct = $weekProductSelectionService->getData($limit);

        return ApiResponse::handle(
            WeekProductCatalogResource::collection($weekProduct),
        );
    }

    public function catalog(
        WeekProductRequest $request,
        WeekProductCatalogService $weekProductService,
    ): JsonResponse {
        $data = $request->validated();

        $weekProductDTO = WeekProductParamsDTO::make(Arr::except($data, 'filter'));
        $filtersDTO = WeekProductFiltersDTO::make(Arr::get($data, 'filter', []));

        $weekProductData = $weekProductService->getCatalogData($weekProductDTO, $filtersDTO, json_encode($request->all()));

        return ApiResponse::handle(
            WeekProductResource::make($weekProductData),
        );
    }
}
