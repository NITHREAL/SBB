<?php

namespace Domain\CouponCategory\Services\CouponCategory;

use Domain\Image\Helpers\ImageUrlHelper;
use Domain\Image\Models\Attachment;
use Domain\Image\Services\ImageSelection;
use Domain\CouponCategory\Models\CouponCategory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CouponCategorySelectService
{
    private const DEFAULT_CAROUSEL_LIMIT = 4;

    private const DEFAULT_PAGINATED_LIMIT = 8;

    public function getCouponCategories(int $limit = null): Collection
    {
        $limit = $limit ?? self::DEFAULT_CAROUSEL_LIMIT;

        $couponCategories = CouponCategory::query()
            ->baseQuery()
            ->whereActive()
            ->limit($limit)
            ->get();

        return $this->getPreparedCouponCategoriesCollection($couponCategories);
    }

    public function getCouponCategoriesPaginated(int $limit = null): LengthAwarePaginator
    {
        $limit = $limit ?? self::DEFAULT_PAGINATED_LIMIT;

        $couponCategories = CouponCategory::query()
            ->baseQuery()
            ->whereActive()
            ->paginate($limit);

        return $couponCategories->setCollection(
            $this->getPreparedCouponCategoriesCollection($couponCategories->getCollection())
        );
    }

    public function getOneCouponCategory(int $id): object
    {
        $couponCategory = CouponCategory::query()
            ->baseQuery()
            ->whereActive()
            ->where('coupon_categories.id', $id)
            ->first();

        $images = ImageSelection::getCouponCategoriesImages([$couponCategory->id]);

        if ($mainImage = Attachment::query()->whereId($couponCategory->image_id)->first()) {
            $mainImage->owner_id = $couponCategory->id;

            $images->prepend($mainImage);
        }

        return $this->getPreparedCouponCategory($couponCategory, $images);
    }

    private function getPreparedCouponCategoriesCollection(Collection $couponCategories): Collection
    {
        $mainImages = Attachment::query()
            ->whereIn('id', $couponCategories->pluck('image_id')->toArray())
            ->get();

        $images = ImageSelection::getCouponCategoriesImages(
            $couponCategories->pluck('id')->toArray()
        );

        return $couponCategories->map(fn (object $couponCategory) => $this->getPreparedCouponCategory($couponCategory, $mainImages->merge($images)));
    }

    public function getPreparedCouponCategory(object $couponCategory, Collection $images): object
    {
        $mainImage = $images->firstWhere('id', $couponCategory->image_id);

        if ($mainImage) {
            $couponCategory->imageUrl = ImageUrlHelper::getUrl($mainImage);
        }

        $couponCategory->imagesData = $images
            ->filter(fn (object $image) => $image->owner_id === $couponCategory->id)
            ->map(fn (object $image) => ImageUrlHelper::getUrl($image))
            ->toArray();

        return $couponCategory;
    }
}
