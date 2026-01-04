<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Domain\Product\Requests\Catalog\PurchasedProductRequest;
use Domain\User\DTO\Product\PurchasedProductDTO;
use Domain\User\Models\User;
use Domain\User\Resources\Product\UserProductsResource;
use Domain\User\Services\Product\PurchasedProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes as SA;
use Symfony\Component\HttpFoundation\Response;

class UserProductController extends Controller
{
    #[
        SA\Endpoint(
            title: 'Ранее купленные товары',
            description: 'отдаёт список товаров, которые ранее были куплены пользователем',
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function purchased(
        PurchasedProductRequest $request,
        PurchasedProductService $productService
    ): JsonResponse {
        $data = $request->validated();

        /** @var User $user */
        $user = Auth::user();

        $purchasedProductDTO = PurchasedProductDTO::make($data, $user);

        $products = $productService->purchasedProducts($purchasedProductDTO);

        return ApiResponse::handle(
            UserProductsResource::make($products)
        );
    }
}
