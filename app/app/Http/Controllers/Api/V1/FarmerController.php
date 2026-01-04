<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Domain\Farmer\Models\Farmer;
use Domain\Farmer\Requests\FarmerInfoRequest;
use Domain\Farmer\Requests\FarmerRequest;
use Domain\Farmer\Resources\FarmerCollectionResource;
use Domain\Farmer\Resources\FarmerInfoResource;
use Domain\Farmer\Resources\FarmerResource;
use Domain\Farmer\Resources\Review\FarmerReviewCollectionResource;
use Domain\Farmer\Services\FarmerReviewService;
use Domain\Farmer\Services\FarmerService;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes as SA;
use Knuckles\Scribe\Attributes\UrlParam;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;

#[
    SA\Group('v1'),
    SA\Subgroup('фермеры'),
    SA\Authenticated
]
class FarmerController extends Controller
{
    #[
        SA\Endpoint('список фермеров', authenticated: true),
        SA\ResponseFromApiResource(
            name: FarmerResource::class,
            model: Farmer::class,
            status: Response::HTTP_OK,
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function index(
        FarmerRequest $request,
        FarmerService $farmerService,
    ): JsonResponse {
        $limit = (int) Arr::get($request->validated(), 'limit');

        $farmers =  $farmerService->getFarmers($limit);

        return ApiResponse::handle(
            FarmerCollectionResource::make($farmers)
        );
    }

    #[
        SA\Endpoint('страница фермера', authenticated: true),
        SA\ResponseFromApiResource(
            name: FarmerResource::class,
            model: Farmer::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[UrlParam('slug', 'string', 'slug фермера', required: true, example: 'texas-ranger')]
    public function show(
        string $slug,
        FarmerRequest $request,
        FarmerService $farmerService,
    ): JsonResponse {
        $limit = (int) Arr::get($request->validated(), 'limit');

        return ApiResponse::handle(
            FarmerInfoResource::make($farmerService->getFarmerBySlug($slug, $limit))
        );
    }

    public function getFarmerReviews(
        string $slug,
        FarmerInfoRequest $request,
        FarmerReviewService $farmerReviewService,
    ): JsonResponse {
        $limit = (int) Arr::get($request->validated(), 'limit');

        $farmers = $farmerReviewService->getFarmersReview($slug, $limit);

        return ApiResponse::handle(
            FarmerReviewCollectionResource::make($farmers)
        );
    }
}
