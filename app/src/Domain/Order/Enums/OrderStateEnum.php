<?php

namespace Domain\Order\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self pending()
 * @method static self finished()
 */
class OrderStateEnum extends Enum
{
    private const STATE_PENDING = 'pending';

    private const STATE_ENDED = 'finished';

    protected static function labels(): array
    {
        return [
            self::STATE_PENDING => 'В процессе',
            self::STATE_ENDED   => 'Завершенный',
        ];
    }
}
