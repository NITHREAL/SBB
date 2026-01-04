<?php

namespace Domain\User\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self male()
 * @method static self female()
 */
class UserSexEnum extends Enum
{
    private const MALE = "male";
    private const FEMALE = "female";

    protected static function labels(): array
    {
        return [
            'male'       => __('user.sex.male'),
            'female'     => __('user.sex.female'),
        ];
    }
}
