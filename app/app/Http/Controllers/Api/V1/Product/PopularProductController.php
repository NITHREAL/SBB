<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use Domain\Product\DTO\Product\PopularProductFiltersDTO;
use Domain\Product\DTO\Product\PopularProductParamsDTO;
use Domain\Product\Requests\PopularProduct\PopularProductRequest;
use Domain\Product\Resources\Catalog\PopularProductCatalogResource;
use Domain\Product\Resources\Catalog\PopularProductResource;
use Domain\Product\Services\PopularProduct\PopularProductCatalogService;
use Domain\Product\Services\PopularProduct\PopularProductSelectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Infrastructure\Http\Responses\ApiResponse;

class PopularProductController extends Controller
{
    public function index(
        PopularProductRequest $request,
        PopularProductSelectionService $popularProductSelectionService,
    ): JsonResponse {
        $limit = Arr::get($request->validated(), 'limit');

        $popularProducts = $popularProductSelectionService->getData($limit);

        return ApiResponse::handle(
            PopularProductCatalogResource::collection($popularProducts),
        );
    }

    public function catalog(
        PopularProductRequest $request,
        PopularProductCatalogService $popularProductService,
    ): JsonResponse {
        $data = $request->validated();

        $popularProductDTO = PopularProductParamsDTO::make(Arr::except($data, 'filter'));
        $filtersDTO = PopularProductFiltersDTO::make(Arr::get($data, 'filter', []));

        $popularProductData = $popularProductService->getCatalogData($popularProductDTO, $filtersDTO, json_encode($request->all()));

        return ApiResponse::handle(
            PopularProductResource::make($popularProductData),
        );
    }
}
