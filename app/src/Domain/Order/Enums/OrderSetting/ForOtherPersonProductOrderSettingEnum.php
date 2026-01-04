<?php

namespace Domain\Order\Enums\OrderSetting;

use Spatie\Enum\Enum;

/**
 * @method static self forOtherPerson()
 * @method static self otherPersonPhoneNumber()
 * @method static self otherPersonName()
 */
class ForOtherPersonProductOrderSettingEnum extends Enum
{
    private const FOR_OTHER_PERSON = 'for_other_person';
    private const OTHER_PERSON_PHONE_NUMBER = 'other_person_phone';
    private const OTHER_PERSON_NAME = 'other_person_name';

    public static function values(): array
    {
        return [
            'forOtherPerson'            => self::FOR_OTHER_PERSON,
            'otherPersonPhoneNumber'    => self::OTHER_PERSON_PHONE_NUMBER,
            'otherPersonName'           => self::OTHER_PERSON_NAME,
        ];
    }
}
