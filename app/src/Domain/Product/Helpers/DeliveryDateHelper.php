<?php

namespace Domain\Product\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Infrastructure\Enum\DaysOfWeek;

class DeliveryDateHelper
{
    private const CANNOT_PREORDER_DAYS = 2;

    public static function getNearestDateSupply(array $days): string
    {
        $days = array_filter(
            $days,
            fn (string $day) => in_array($day, DaysOfWeek::toValues(), true),
        );

        $dates = array_map(
            fn (string $day) => Carbon::now()->startOfDay()->next($day),
            $days,
        );

        $nearest = Arr::first(Arr::sort($dates));

        return $nearest?->format('Y-m-d');
    }

    public static function getNearestDateDelivery(array $days): array
    {
        $dates = array_map(
            fn (string $day) => Carbon::make($day),
            $days,
        );

        $dates = Arr::sort($dates);

        return array_map(
            fn ($day) => $day->format('Y-m-d'),
            array_values($dates),
        );
    }
}
