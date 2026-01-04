<?php

namespace Domain\PromoAction\Services;

use Domain\PromoAction\Models\PromoAction;
use Illuminate\Support\Facades\Cache;

class PromoActionSelection
{
    private const CACHE_KEY_PREFIX = 'promo_action';

    private const CACHE_TTL = 10800;

    public static function getPromoActionBySlug(string $slug): PromoAction
    {
        return Cache::remember(
            self::getCacheKey($slug),
            self::CACHE_TTL,
            fn() => PromoAction::query()->where('slug', $slug)->firstOrFail(),
        );
    }

    private static function getCacheKey(string $slug): string
    {
        return sprintf('%s_%s', self::CACHE_KEY_PREFIX, $slug);
    }
}
