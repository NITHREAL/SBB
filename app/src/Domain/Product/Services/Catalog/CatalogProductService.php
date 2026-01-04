<?php

namespace Domain\Product\Services\Catalog;

use Domain\Product\DTO\Product\ProductByBarcodeDTO;
use Domain\Product\DTO\Product\ProductBySlugDTO;
use Domain\Product\Models\Product;
use Domain\Product\Models\Review;
use Domain\Product\Services\ProductCollectionService;
use Domain\Product\Services\RelatedProduct\ProductRelatedProductsService;
use Domain\User\Models\User;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CatalogProductService
{
    public function __construct(
        private readonly ProductCollectionService $productCollectionService,
        private readonly ProductRelatedProductsService $relatedProductsService,
    ) {
    }

    public function getProductBySlug(
        ProductBySlugDTO $productBySlugDTO
    ): object  {
        $slug = $productBySlugDTO->getSlug();

        /** @var Product $product */
        $product = Product::query()
            ->detailQuery($slug)
            ->whereStoreOneCId($productBySlugDTO->getStore1CId())
            ->first();

        if (is_null($product)) {
            throw new NotFoundHttpException("товара с slug: $slug не существует");
        }

        $product->relatedProductsData = $this->getRelatedProducts(
            $product,
            $productBySlugDTO->getStore1CId(),
            $productBySlugDTO->getRelatedProductsLimit()
        );
        $product->is_review_availability = $this->getProductReviewAvailability($product, $productBySlugDTO->getUser());

        $product->reviewCount = $this->getProductReviewCount($product);

        return $this->productCollectionService->getPreparedProduct($product);
    }

    public function getProductByBarcode(ProductByBarcodeDTO $productByBarcodeDTO): object
    {
        $barcode = $productByBarcodeDTO->getBarcode();
        $storeOneCId = $productByBarcodeDTO->getStore1CId();

        /** @var Product $product */
        $product = Product::query()
            ->searchByBarcodeQuery($barcode)
            ->whereStoreOneCId($storeOneCId)
            ->first();

        if (is_null($product)) {
            throw new NotFoundHttpException("Товара с barcode: $barcode не существует");
        }

        $product->relatedProductsData = $this->getRelatedProducts(
            $product,
            $storeOneCId,
            $productByBarcodeDTO->getRelatedProductsLimit()
        );
        $product->is_review_availability = $this->getProductReviewAvailability($product, $productByBarcodeDTO->getUser());

        $product->reviewCount = $this->getProductReviewCount($product);

        return $this->productCollectionService->getPreparedProduct($product);
    }

    private function getRelatedProducts(
        Product $product,
        string $store1cId,
        ?int $relatedProductsLimit,
    ): Collection {
        $products = $this->relatedProductsService->getRelatedProducts(
            [$product->id],
            $store1cId,
            $relatedProductsLimit,
        );

        return $this->productCollectionService->getPreparedProductsCollection($products);
    }

    private function getProductReviewAvailability(Product $product, ?User $user): bool {
        return $user &&
            !Review::query()
                ->whereUser($user->id)
                ->whereProductId($product->id)
                ->whereActive()
                ->exists();
    }

    private function getProductReviewCount(Product $product): int
    {
        return Review::query()
            ->whereProductId($product->id)
            ->whereActive()
            ->count();
    }
}
