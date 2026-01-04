<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use Domain\Product\DTO\Catalog\CatalogFiltersDTO;
use Domain\Product\DTO\Catalog\CatalogParamsDTO;
use Domain\Product\DTO\Product\ProductByBarcodeDTO;
use Domain\Product\DTO\Product\ProductBySlugDTO;
use Domain\Product\DTO\Search\SearchFiltersDTO;
use Domain\Product\DTO\Search\SearchParamsDTO;
use Domain\Product\Models\Product;
use Domain\Product\Requests\Catalog\GetProductRequest;
use Domain\Product\Requests\Catalog\ProductCatalogPreviewRequest;
use Domain\Product\Requests\Catalog\ProductCatalogRequest;
use Domain\Product\Requests\Search\SearchRequest;
use Domain\Product\Resources\Catalog\CatalogPreview\CatalogPreviewResource;
use Domain\Product\Resources\Catalog\CatalogProductResource;
use Domain\Product\Resources\Catalog\CatalogResource;
use Domain\Product\Resources\Catalog\ProductDetailResource;
use Domain\Product\Resources\Search\SearchByBarcodeResource;
use Domain\Product\Resources\Search\SearchResource;
use Domain\Product\Services\Catalog\CatalogPreviewService;
use Domain\Product\Services\Catalog\CatalogProductService;
use Domain\Product\Services\Catalog\CatalogService;
use Domain\Product\Services\Search\SearchService;
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
class ProductController extends Controller
{
    #[
        SA\Endpoint(
            title: 'Получение товаров категории',
            description: 'отдаёт список товаров каталога относящихся к переданной категории',
        ),
        SA\ResponseFromApiResource(
            name: CatalogResource::class,
            model: Product::class,
            status: Response::HTTP_OK,
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function index(
        string                $categorySlug,
        ProductCatalogRequest $request,
        CatalogService        $productCatalogService,
    ): JsonResponse {
        $data = $request->validated();

        $catalogDTO = CatalogParamsDTO::make(
            Arr::except($data, 'filter'),
            $categorySlug,
        );
        $filtersDTO = CatalogFiltersDTO::make(Arr::get($data, 'filter', []));

        $catalogData = $productCatalogService->getCatalogData($catalogDTO, $filtersDTO, json_encode($request->all()));

        return ApiResponse::handle(
            CatalogResource::make($catalogData),
        );
    }

    #[
        SA\Endpoint(
            title: 'Получение превью категории',
            description: 'отдаёт товары для превью категории',
        ),
        SA\ResponseFromApiResource(
            name: CatalogResource::class,
            model: Product::class,
            status: Response::HTTP_OK,
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function preview(
        string $categorySlug,
        ProductCatalogPreviewRequest $request,
        CatalogPreviewService $catalogPreviewService,
    ): JsonResponse {
        $data = $request->validated();

        $categoryPreviewData = $catalogPreviewService->getCatalogPreview(
            $categorySlug,
            Arr::get($data, 'limit'),
        );

        return ApiResponse::handle(
            CatalogPreviewResource::make($categoryPreviewData),
        );
    }

    #[
        SA\Endpoint(
            title: 'поиск товаров',
            description: 'отдаёт список товаров каталога найденных при помощи полнотекстового поиска',
        ),
        SA\ResponseFromApiResource(
            name: SearchResource::class,
            model: Product::class,
            status: Response::HTTP_OK,
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function search(
        SearchRequest $request,
        SearchService $searchService,
    ): JsonResponse {
        $data = $request->validated();

        $searchDTO = SearchParamsDTO::make(Arr::except($data, 'filter'));
        $filtersDTO = SearchFiltersDTO::make(Arr::get($data, 'filter', []));

        $searchData = $searchService->getSearchData($searchDTO, $filtersDTO, json_encode($request->all()));

        return ApiResponse::handle(
            SearchResource::make($searchData),
        );
    }
    #[
        SA\Endpoint(
            title: 'поиск товара по штрихкоду',
            description: 'отдаёт товар найденный по штрихкоду',
        ),
        SA\ResponseFromApiResource(
            name: SearchByBarcodeResource::class,
            model: Product::class,
            status: Response::HTTP_OK,
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function searchByBarcode(
        string $barcode,
        CatalogProductService $catalogProductService,
    ): JsonResponse {
        $data = [
            'barcode' => $barcode,
            'user'    => Auth::user(),
        ];

        $productByBarcodeDTO = ProductByBarcodeDTO::make($data);

        $product = $catalogProductService->getProductByBarcode($productByBarcodeDTO);

        return ApiResponse::handle(
            SearchByBarcodeResource::make([
                'product' => $product,
                'barcode' => $barcode,
            ]),
        );
    }

    #[
        SA\Endpoint(
            title: 'карточка товара',
            description: 'детальная информация о указанном товаре',
        ),
        SA\ResponseFromApiResource(
            name: ProductDetailResource::class,
            model: Product::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_NOT_FOUND, description: 'Entity not found'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[UrlParam('slug', 'string', 'slug товара', required: true, example: 'lavash')]
    public function show(
        string $slug,
        GetProductRequest $request,
        CatalogProductService $catalogService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $productBySlugDTO = ProductBySlugDTO::make($request->validated(), $slug, $user);

        return ApiResponse::handle(
            ProductDetailResource::make(
                $catalogService->getProductBySlug($productBySlugDTO)
            ),
        );
    }

    #[
        SA\Endpoint(
            title: 'с этим товаром покупают',
            description: 'товары которые были в одном заказе с выбранным.если таких товаров нет - из смежной категории',
        ),
        SA\ResponseFromApiResource(
            name: CatalogProductResource::class,
            model: Product::class,
            status: Response::HTTP_OK,
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_NOT_FOUND, description: 'Entity not found'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[UrlParam('slug', 'string', 'slug товара', required: true, example: 'lavash')]
    public function getRelatedProducts(
        string $slug,
    ): JsonResponse {
        $product = Product::where('slug', $slug)->first();

        return ApiResponse::handle(
            CatalogProductResource::collection(
                $product->relatedProducts
            )
        );
    }
}
