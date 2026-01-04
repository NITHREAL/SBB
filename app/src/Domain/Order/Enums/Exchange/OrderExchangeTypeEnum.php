<?php

namespace Domain\Order\Enums\Exchange;

use Spatie\Enum\Enum;

/**
 * @method static self import()
 * @method static self export()
 */
class OrderExchangeTypeEnum extends Enum
{
    protected static function labels(): array
    {
        return [
            'import' => 'Импорт',
            'export' => 'Экспорт'
        ];
    }
}
