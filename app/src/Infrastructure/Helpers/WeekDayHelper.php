<?php

namespace Infrastructure\Helpers;

use Illuminate\Support\Arr;

class WeekDayHelper
{
    public static function getPreparedWeekday(string $weekday): string
    {
        return Arr::get(self::getWeekdays(), $weekday, $weekday);
    }

    public static function getWeekdays(): array
    {
        return [
            'monday'    => 'Понедельник',
            'tuesday'   => 'Вторник',
            'wednesday' => 'Среда',
            'thursday'  => 'Четверг',
            'friday'    => 'Пятница',
            'saturday'  => 'Суббота',
            'sunday'    => 'Воскресенье',
        ];
    }
}
