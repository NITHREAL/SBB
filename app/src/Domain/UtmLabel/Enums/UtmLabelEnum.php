<?php

namespace Domain\UtmLabel\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self utmSource()
 * @method static self utmMedium()
 * @method static self utmCampaign()
 * @method static self utmTerm()
 * @method static self utmContent()
 */
class UtmLabelEnum extends Enum
{
    protected static function labels(): array
    {
        return [
            'utmSource'   => 'Источник',
            'utmMedium'   => 'Рекламная модель',
            'utmCampaign' => 'Рекламная кампания',
            'utmTerm'     => 'Ключевая фраза',
            'utmContent'  => 'Элемент контента',
        ];
    }

    protected static function values()
    {
        return [
            'utmSource'   => 'utm_source',
            'utmMedium'   => 'utm_medium',
            'utmCampaign' => 'utm_campaign',
            'utmTerm'     => 'utm_term',
            'utmContent'  => 'utm_content',
        ];
    }

    public static function toArrayWithValues(): array
    {
        $array = self::toArray();

        foreach ($array as $key => &$value) {
            $value .= " ({$key})";
        }

        return $array;
    }
}
