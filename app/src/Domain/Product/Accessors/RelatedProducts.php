<?php

namespace Domain\Product\Accessors;

use Domain\Product\Models\Product;
use Domain\Product\Services\ProductCollectionService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

final class RelatedProducts
{
    private const DEFAULT_RELATED_PRODUCT_LIMIT = 4;
    private const CACHE_KEY_PREFIX = 'related_products';
    private const CACHE_HOURS_TTL = 24;

    public function __construct(
        private readonly Product $product
    ) {
    }

    public function __invoke()
    {
        // TODO добавить логику по формированию связанных товаров из заказов

        $slug = $this->product->slug;

        return Cache::remember(
            $this->getCacheKey($slug),
            now()->addHours(self::CACHE_HOURS_TTL),
            fn() => $this->getRelatedProducts($slug),
        );
    }

    private function getRelatedProducts(string $slug): Collection
    {
        $productCollectionService = app(ProductCollectionService::class);

        $categoryIds = $this->product->categories()->pluck('system_id');

        $products = Product::query()
            ->smallProductCardsQuery(BuyerStore::getSelectedStore()->getAttribute('system_id'))
        ->whereHas('categories', static function ($query) use ($categoryIds) {
            $query->whereIn('system_id', $categoryIds);
        })->take(self::DEFAULT_RELATED_PRODUCT_LIMIT)->get();

        return $productCollectionService->getPreparedProductsCollection($products);
    }

    private function getCacheKey(string $slug): string
    {
        return sprintf('%s_%s', self::CACHE_KEY_PREFIX, $slug);
    }
}
