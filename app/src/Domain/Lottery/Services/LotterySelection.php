<?php

namespace Domain\Lottery\Services;

use Domain\Lottery\Models\Lottery;
use Illuminate\Support\Facades\Cache;

readonly class LotterySelection
{
    private const CACHE_KEY_PREFIX = 'lottery';

    private const CACHE_TTL = 10800;

    public static function getLotteryBySlug(string $slug): Lottery
    {
        return Cache::remember(
            self::getCacheKey($slug),
            self::CACHE_TTL,
            fn() => Lottery::query()->whereSlug($slug)->firstOrFail(),
        );
    }

    private static function getCacheKey(string $slug): string
    {
        return sprintf('%s_%s', self::CACHE_KEY_PREFIX, $slug);
    }
}
