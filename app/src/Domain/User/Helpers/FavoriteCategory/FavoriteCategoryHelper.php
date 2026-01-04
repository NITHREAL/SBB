<?php

namespace Domain\User\Helpers\FavoriteCategory;

use Illuminate\Support\Carbon;

class FavoriteCategoryHelper
{
    private const INVALID_DAYS_UNTIL_MONTH_END = 3;

    private const PERIOD_FORMAT = 'm-y';

    public static function isPeriodAvailable(string $period): bool
    {
        $nowPeriod = self::getCurrentMonthPeriod();
        $nextMonthPeriod = self::getNextMonthPeriod();

        return match ($period) {
            $nowPeriod          => self::isCurrentMonthChangesAvailable(),
            $nextMonthPeriod    => self::isNextMonthChangesAvailable(),
            default             => false,
        };
    }

    public static function getCurrentAvailablePeriod(): ?string
    {
        return match(true) {
            self::isCurrentMonthChangesAvailable()  => self::getCurrentMonthPeriod(),
            self::isNextMonthChangesAvailable()     => self::getNextMonthPeriod(),
            default                                 => null,
        };
    }

    public static function getActualPeriods(): array
    {
        return [
            self::getCurrentMonthPeriod(),
            self::getNextMonthPeriod(),
        ];
    }

    public static function getCurrentMonthPeriod(): string
    {
        return Carbon::now()->format(self::PERIOD_FORMAT);
    }

    public static function getNextMonthPeriod(): string
    {
        return Carbon::now()->addMonth()->format(self::PERIOD_FORMAT);
    }

    private static function isCurrentMonthChangesAvailable(): bool
    {
        return Carbon::now() < self::getCurrentMonthLastValidDate();
    }

    private static function isNextMonthChangesAvailable(): bool
    {
        return Carbon::now() >= self::getCurrentMonthLastValidDate();
    }

    private static function getCurrentMonthLastValidDate(): Carbon
    {
        return Carbon::now()
            ->endOfDay()
            ->endOfMonth()
            ->subDays(self::INVALID_DAYS_UNTIL_MONTH_END);
    }
}
