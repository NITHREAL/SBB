<?php

namespace Domain\Product\Accessors;

use Domain\Image\Services\ImageSelection;
use Domain\Product\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

final class ProductImages
{
    private const CACHE_KEY_PREFIX = 'product_images';
    private const CACHE_HOURS_TTL = 24;

    public function __construct(private readonly Product $product)
    {
    }

    public function __invoke()
    {
        $id = $this->product->id;

        return Cache::remember(
            $this->getCacheKey($this->product->slug),
            now()->addHours(self::CACHE_HOURS_TTL),
            fn() => $this->getProductImages($id),
        );
    }

    private function getProductImages(int $id): Collection
    {
        $images = ImageSelection::getProductsImages([$id]);

        return $images->map(static function ($item) {
            return [
                'image' => $item->stream,
                'blur_hash' => $item->blur_hash,
            ];
        }, $images);
    }

    private function getCacheKey(string $slug): string
    {
        return sprintf('%s_%s', self::CACHE_KEY_PREFIX, $slug);
    }
}
