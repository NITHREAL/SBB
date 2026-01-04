<?php

namespace App\Http\Controllers\Api\V1\Coupon;

use App\Http\Controllers\Controller;
use Domain\CouponCategory\Requests\CouponCategoryListRequest;
use Domain\CouponCategory\Requests\CouponCategoriesRequest;
use Domain\CouponCategory\Resources\CouponCategoriesListResource;
use Domain\CouponCategory\Resources\CouponCategoryResource;
use Domain\CouponCategory\Resources\CouponCategoryShowResource;
use Domain\CouponCategory\Services\CouponCategory\CouponCategorySelectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Infrastructure\Http\Responses\ApiResponse;

class CouponController extends Controller
{
    public function index(
        CouponCategoriesRequest     $request,
        CouponCategorySelectService $couponCategorySelectService
    ): JsonResponse {
        $limit = Arr::get($request->validated(), 'limit');

        $couponCategories = $couponCategorySelectService->getCouponCategories($limit);

        return ApiResponse::handle(
            CouponCategoryResource::collection($couponCategories),
        );
    }

    public function list(
        CouponCategoryListRequest $request,
        CouponCategorySelectService $couponCategorySelectService
    ): JsonResponse {
        $limit = Arr::get($request->validated(), 'limit');

        $couponCategories = $couponCategorySelectService->getCouponCategoriesPaginated($limit);

        return ApiResponse::handle(
            CouponCategoriesListResource::make($couponCategories),
        );
    }

    public function show(
        int $id,
        CouponCategorySelectService $couponCategorySelectService
    ): JsonResponse {
        $couponCategory = $couponCategorySelectService->getOneCouponCategory($id);

        return ApiResponse::handle(
            CouponCategoryShowResource::make($couponCategory),
        );
    }
}
