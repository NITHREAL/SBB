<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use Domain\Product\Models\Category;
use Domain\Product\Resources\Category\CategoryPageResource;
use Domain\Product\Resources\Category\CategoryResource;
use Domain\Product\Resources\Category\CategoryTreeResource;
use Domain\Product\Services\Category\CategoryPageService;
use Domain\Product\Services\Category\CategoryTreeService;
use Domain\Product\Services\Category\MainCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes as SA;
use Symfony\Component\HttpFoundation\Response;

#[
    SA\Group('v1'),
    SA\Subgroup('категории')
]
class CategoryController extends Controller
{
    #[
        SA\Endpoint(
            title: 'категории товаров',
            description: 'дерево категорий товаров',
        ),
        SA\ResponseFromApiResource(
            name: CategoryTreeResource::class,
            model: Category::class,
            status: Response::HTTP_OK,
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function categoryTree(
        Request $request,
        CategoryTreeService $categoryReceiveService,
    ): JsonResponse {
        $store1cId = $request->get('storeOneCId');

        $categories = $categoryReceiveService->getCategoryTree($store1cId);

        return ApiResponse::handle(
            CategoryTreeResource::collection($categories),
        );
    }

    public function mainCategories(MainCategoryService $mainCategoriesService): JsonResponse
    {
        $mainCategoriesService = $mainCategoriesService->getMainCategories();

        return ApiResponse::handle(
            CategoryResource::collection($mainCategoriesService),
        );
    }

    public function category(
        string $slug,
        CategoryPageService $categoryPageService,
    ): JsonResponse {
        $category = $categoryPageService->getCategory($slug);

        return ApiResponse::handle(
            CategoryPageResource::make($category),
        );
    }
}
