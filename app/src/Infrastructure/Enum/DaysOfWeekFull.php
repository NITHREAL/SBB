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
class DaysOfWeekFull extends Enum
{
    protected static function labels(): array
    {
        return [
            'monday'    => 'Понедельник',
            'tuesday'   => 'Вторник',
            'wednesday' => 'Среда',
            'thursday'  => 'Четверг',
            'friday'    => 'Пятница',
            'saturday'  => 'Суббота',
            'sunday'    => 'Воскресенье'
        ];
    }
}
