<?php

namespace Domain\Image\Services;

use Domain\Image\Models\Attachment;
use Illuminate\Support\Collection;

class ImageSelection
{
    public static function getCategoriesImages(array $categoryIds): Collection
    {
        return self::getImagesByOwners($categoryIds, 'category');
    }

    public static function getProductsImages(array $productIds): Collection
    {
        return self::getImagesByOwners($productIds, 'product');
    }

    public static function getFarmersImages(array $farmerIds): Collection
    {
        return self::getImagesWithDescription($farmerIds, 'farmer');
    }

    public static function getCouponCategoriesImages(array $couponCategoryIds): Collection
    {
        return self::getImagesByOwners($couponCategoryIds, 'couponCategory');
    }

    private static function getImagesByOwners(array $ownerIds, string $ownerType): Collection
    {
        return Attachment::query()
            ->baseQuery()
            ->whereOwners($ownerIds, $ownerType)
            ->get();
    }

    private static function getImagesWithDescription(array $ownerIds, string $ownerType): Collection
    {
        return Attachment::query()
            ->descriprionQuery()
            ->whereOwners($ownerIds, $ownerType)
            ->get();
    }
}
