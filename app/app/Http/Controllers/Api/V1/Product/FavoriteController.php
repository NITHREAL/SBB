<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use Domain\Product\Models\Favorite;
use Domain\Product\Models\Product;
use Domain\Product\Requests\Favorite\FavoriteRequest;
use Domain\Product\Resources\Catalog\CatalogProductResource;
use Domain\Product\Resources\Favorite\FavoriteResource;
use Domain\Product\Services\Favorite\FavoriteProductsService;
use Domain\Product\Services\Favorite\FavoriteService;
use Domain\User\Resources\Auth\BuyerTokenResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes as SA;
use Knuckles\Scribe\Attributes\UrlParam;
use Symfony\Component\HttpFoundation\Response;

#[
    SA\Group('v1'),
    SA\Subgroup('избранные товары')
]
class FavoriteController extends Controller
{
    #[
        SA\Endpoint(
            title: 'список избранных товаров',
            description: 'отдаёт список избранных товаров',
        ),
        SA\ResponseFromApiResource(
            name: CatalogProductResource::class,
            model: Product::class,
            status: Response::HTTP_OK,
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function index(
        FavoriteRequest         $request,
        FavoriteProductsService $favoriteProductsService,
    ): JsonResponse {
        $data = $request->validated();

        $productsData = $favoriteProductsService->getFavoriteProductsData(
            Arr::get($data, 'limit'),
            Arr::get($data, 'storeOneCId'),
        );

        return ApiResponse::handle(
            FavoriteResource::make($productsData),
        );
    }

    #[
        SA\Endpoint(
            title: 'добавление товара в избранное',
            description: 'добавление товара в избранное',
        ),
        SA\ResponseFromApiResource(
            name: BuyerTokenResource::class,
            model: Favorite::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[UrlParam('id', 'integer', 'ID товара', required: true, example: 10)]
    public function addToFavorite(
        int $productId,
        FavoriteService $favoriteService,
    ): JsonResponse {
        $favorite = $favoriteService->addProduct($productId);

        return ApiResponse::handle(
            BuyerTokenResource::make($favorite),
        );
    }

    #[
        SA\Endpoint(
            title: 'добавление товара в избранное',
            description: 'добавление товара в избранное',
        ),
        SA\ResponseFromApiResource(
            name: BuyerTokenResource::class,
            model: Favorite::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[UrlParam('id', 'integer', 'ID товара', required: true, example: 10)]
    public function deleteFromFavorite(
        int $productId,
        FavoriteService $favoriteService,
    ): JsonResponse {
        $favorite = $favoriteService->deleteProduct($productId);

        return ApiResponse::handle(
            BuyerTokenResource::make($favorite),
        );
    }
}
