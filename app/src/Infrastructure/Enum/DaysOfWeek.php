<?php

namespace Infrastructure\Enum;

use Spatie\Enum\Enum;

/**
 * @method static self monday()
 * @method static self tuesday()
 * @method static self wednesday()
 * @method static self thursday()
 * @method static self friday()
 * @method static self saturday()
 * @method static self sunday()
 */
class DaysOfWeek extends Enum
{
    protected static function labels(): array
    {
        return [
            'monday'    => 'Пн',
            'tuesday'   => 'Вт',
            'wednesday' => 'Ср',
            'thursday'  => 'Чт',
            'friday'    => 'Пт',
            'saturday'  => 'Сб',
            'sunday'    => 'Вс'
        ];
    }
}
