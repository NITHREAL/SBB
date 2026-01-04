<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Stores;

use App\Http\Controllers\Controller;
use Domain\Store\Models\Store;
use Domain\Store\Resources\StoreResource;
use Domain\Store\Services\StoreService;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes as SA;
use Knuckles\Scribe\Attributes\UrlParam;
use Symfony\Component\HttpFoundation\Response;

#[
    SA\Group('v1'),
    SA\Subgroup('магазины'),
    SA\Authenticated
]
class StoreController extends Controller
{
    #[
        SA\Endpoint('список магазинов'),
        SA\ResponseFromApiResource(
            name: StoreResource::class,
            model: Store::class,
            status: Response::HTTP_OK,
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function index(StoreService $storeService): JsonResponse
    {
        $stores = $storeService->getStores();

        return ApiResponse::handle(
            StoreResource::collection($stores),
        );
    }

    public function getByCity(
        int $cityId,
        StoreService $storeService,
    ): JsonResponse {
        $stores = $storeService->geStoresByCity($cityId);

        return ApiResponse::handle(
            StoreResource::collection($stores),
        );
    }

    #[
        SA\Endpoint('выбранный магазин'),
        SA\Response(content: '', status: Response::HTTP_OK, description: 'ok'),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[UrlParam('slug', 'string', 'slug магазина', required: true, example: 'big_store')]
    public function show(
        string $slug,
        StoreService $storeService,
    ): JsonResponse {
        $store = $storeService->getStoreBySlug($slug);

        return ApiResponse::handle(
            StoreResource::make($store),
        );
    }
}
