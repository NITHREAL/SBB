<?php

namespace Domain\Farmer\Accessors;

use Domain\Farmer\Models\Farmer;
use Domain\Image\Services\ImageSelection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

final class FarmerImages
{
    private const CACHE_KEY_PREFIX = 'farmer_images';
    private const CACHE_HOURS_TTL = 24;

    public function __construct(private readonly Farmer $farmer)
    {
    }

    public function __invoke()
    {
        $id = $this->farmer->id;

        return Cache::remember(
            $this->getCacheKey($this->farmer->slug),
            now()->addHours(self::CACHE_HOURS_TTL),
            fn() => $this->getFarmerImages($id),
        );
    }

    private function getFarmerImages(int $id): Collection
    {
        $images = ImageSelection::getFarmerImages($id);

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
