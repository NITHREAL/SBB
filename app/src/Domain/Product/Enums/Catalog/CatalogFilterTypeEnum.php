<?php

namespace Domain\Product\Enums\Catalog;

use Spatie\Enum\Enum;

/**
 * @method static self list()
 * @method static self checkbox()
 */
class CatalogFilterTypeEnum extends Enum
{
    protected static function labels(): array
    {
        return [
            'list'      => 'Список',
            'checkbox'  => 'Чекбокс',
        ];
    }
}
