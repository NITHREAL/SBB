<?php

namespace Domain\CouponCategory\Services\CouponCategory;

use Domain\Image\Models\Attachment;
use Domain\CouponCategory\DTO\CouponCategoryDTO;
use Domain\CouponCategory\Models\CouponCategory;
use Illuminate\Support\Str;

class CouponCategoryChangeService
{
    public function create(CouponCategoryDTO $dto): CouponCategory
    {
        $couponCategory = $this->getFilledCouponCategoryInstance(
            new CouponCategory(),
            $dto,
        );

        // TODO убрать генерацию system_id после выяснения источника появления купонов
        $couponCategory->system_id = Str::uuid()->toString();

        $couponCategory->save();

        $this->updateCouponCategoryRelations($couponCategory, $dto);

        return $couponCategory;
    }

    public function update(int $couponCategoryId, CouponCategoryDTO $dto): CouponCategory
    {
        /** @var CouponCategory $couponCategory */
        $couponCategory = CouponCategory::findOrFail($couponCategoryId);

        $couponCategory = $this->getFilledCouponCategoryInstance(
            $couponCategory,
            $dto,
        );

        $this->updateCouponCategoryRelations($couponCategory, $dto);

        return $couponCategory;
    }

    public function delete(int $couponCategoryId): bool
    {
        return CouponCategory::query()->whereId($couponCategoryId)->delete();
    }

    private function updateCouponCategoryRelations(
        CouponCategory $couponCategory,
        CouponCategoryDTO $dto,
    ): void {
        $mainImageId = $dto->getMainImageId();

        if ($mainImageId && $mainImage = Attachment::find($mainImageId)) {
            $couponCategory->mainImage()->associate($mainImage);
        } else {
            $couponCategory->mainImage()->dissociate();
        }

        $couponCategory->images()->sync($dto->getImages());

        $couponCategory->save();
    }

    private function getFilledCouponCategoryInstance(
        CouponCategory $couponCategory,
        CouponCategoryDTO $dto,
    ): CouponCategory {
        $couponCategory->fill([
            'title'             => $dto->getTitle(),
            'description'       => $dto->getDescription(),
            'purchase_terms'    => $dto->getPurchaseTerms(),
            'price'             => $dto->getPrice(),
            'sort'              => $dto->getSort(),
            'active'            => $dto->isActive(),
        ]);

        return $couponCategory;
    }
}
