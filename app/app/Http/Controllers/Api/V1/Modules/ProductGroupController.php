<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Modules;

use App\Http\Controllers\Controller;
use Domain\ProductGroup\DTO\ProductGroupDTO;
use Domain\ProductGroup\DTO\ProductGroupPage\ProductGroupPageFiltersDTO;
use Domain\ProductGroup\DTO\ProductGroupPage\ProductGroupPageParamsDTO;
use Domain\ProductGroup\Models\ProductGroup;
use Domain\ProductGroup\Requests\ProductGroupPageRequest;
use Domain\ProductGroup\Requests\ProductGroupRequest;
use Domain\ProductGroup\Resources\ProductGroupPage\ProductGroupPageResource;
use Domain\ProductGroup\Resources\ProductGroupResource;
use Domain\ProductGroup\Resources\ProductGroupWithProductResource;
use Domain\ProductGroup\Services\ProductGroupPage\ProductGroupPageService;
use Domain\ProductGroup\Services\ProductGroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes as SA;
use Symfony\Component\HttpFoundation\Response;

#[
    SA\Group('v1'),
    SA\Subgroup('модули'),
    SA\Authenticated
]
class ProductGroupController extends Controller
{
    #[
        SA\Endpoint('подборки', authenticated: true),
        SA\ResponseFromApiResource(
            name: ProductGroupResource::class,
            model: ProductGroup::class,
            status: Response::HTTP_OK,
            description: 'без продуктов',
            collection: true,
        ),
        SA\ResponseFromApiResource(
            name: ProductGroupWithProductResource::class,
            model: ProductGroup::class,
            status: Response::HTTP_OK,
            description: 'с продуктами при указании соответствующего параметра',
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function index(
        ProductGroupRequest $request,
        ProductGroupService $productGroupService
    ): JsonResponse {
        $productGroupDTO = ProductGroupDTO::make(
            $request->validated(),
            Auth::user()?->id
        );

        return ApiResponse::handle(
            $productGroupService->getGroups($productGroupDTO)
        );
    }

    public function show(
        string                  $slug,
        ProductGroupPageRequest $request,
        ProductGroupPageService $groupPageService,
    ): JsonResponse {
        $data = $request->validated();

        $paramsDTO = ProductGroupPageParamsDTO::make(Arr::except($data, 'filter'), $slug, Auth::id());
        $filtersDTO = ProductGroupPageFiltersDTO::make(Arr::get($data, 'filter', []));

        $productGroupPageData = $groupPageService->getProductGroupPageData(
            $paramsDTO,
            $filtersDTO,
            json_encode($request->all()),
        );

        return ApiResponse::handle(
            ProductGroupPageResource::make($productGroupPageData),
        );
    }
}
