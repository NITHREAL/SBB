<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use Domain\Product\DTO\Product\ForgottenProductFiltersDTO;
use Domain\Product\DTO\Product\ForgottenProductParamsDTO;
use Domain\Product\Requests\ForgottenProduct\ForgottenProductRequest;
use Domain\Product\Resources\Catalog\ForgottenProductCatalogResource;
use Domain\Product\Resources\Catalog\ForgottenProductResource;
use Domain\Product\Services\ForgottenProduct\ForgottenProductCatalogService;
use Domain\Product\Services\ForgottenProduct\ForgottenProductSelectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Infrastructure\Http\Responses\ApiResponse;

class ForgottenProductController extends Controller
{
    public function index(
        ForgottenProductRequest          $request,
        ForgottenProductSelectionService $forgottenProductSelectionService,
    ): JsonResponse {
        $limit = Arr::get($request->validated(), 'limit');

        $forgottenProducts = $forgottenProductSelectionService->getData($limit);

        return ApiResponse::handle(
            ForgottenProductCatalogResource::collection($forgottenProducts),
        );
    }

    public function catalog(
        ForgottenProductRequest        $request,
        ForgottenProductCatalogService $forgottenProductCatalogService,
    ): JsonResponse {
        $data = $request->validated();

        $forgottenProductDTO = ForgottenProductParamsDTO::make(Arr::except($data, 'filter'));
        $filtersDTO = ForgottenProductFiltersDTO::make(Arr::get($data, 'filter', []));

        $forgottenProductData = $forgottenProductCatalogService
            ->getCatalogData($forgottenProductDTO, $filtersDTO, json_encode($request->all()));

        return ApiResponse::handle(
            ForgottenProductResource::make($forgottenProductData),
        );
    }
}
