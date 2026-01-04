<?php

namespace Domain\Image\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self couponCategory()
 */
class AttachmentableTypeEnum extends Enum
{
    private const COUPON_CATEGORY = 'coupon_category';

    protected static function values(): array
    {
        return [
            'couponCategory' => self::COUPON_CATEGORY,
        ];
    }
}
