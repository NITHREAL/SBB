<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use Domain\Product\DTO\Review\ReviewDTO;
use Domain\Product\Models\Review;
use Domain\Product\Requests\Review\ReviewRequest;
use Domain\Product\Requests\Review\ReviewsRequest;
use Domain\Product\Resources\Catalog\ProductDetailResource;
use Domain\Product\Resources\Review\ReviewResource;
use Domain\Product\Services\Review\ReviewService;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes as SA;
use Knuckles\Scribe\Attributes\UrlParam;
use Symfony\Component\HttpFoundation\Response;

#[
    SA\Group('v1'),
    SA\Subgroup('каталог')
]
class ReviewController extends Controller
{
    #[
        SA\Endpoint(
            title: 'отзывы товара',
            description: 'отзывы пользователей на указанный товар',
        ),
        SA\ResponseFromApiResource(
            name: ProductDetailResource::class,
            model: Review::class,
            status: Response::HTTP_OK,
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[UrlParam('slug', 'string', 'slug товара', required: true, example: 'lavash')]
    public function index(
        string $slug,
        ReviewsRequest $request,
        ReviewService $reviewService,
    ): JsonResponse {
        return ApiResponse::handle(
            ReviewResource::collection(
                $reviewService->getProductReviews($slug, Arr::get($request->validated(), 'limit')),
            ),
        );
    }

    #[
        SA\Endpoint(
            title: 'добавление отзыва на товар',
            description: 'добавить отзыв на выбранный товар',
        ),
        SA\Response(content: '', status: Response::HTTP_NO_CONTENT, description: 'ok'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[UrlParam('slug', 'string', 'slug товара', required: true, example: 'lavash')]
    public function store(
        ReviewService $reviewService,
        ReviewRequest $request,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $reviewDTO = ReviewDTO::make($request->validated(), $user);

        $review = $reviewService->addProductReview($reviewDTO);

        return ApiResponse::handle(
            ReviewResource::make($review)
        );
    }
}
