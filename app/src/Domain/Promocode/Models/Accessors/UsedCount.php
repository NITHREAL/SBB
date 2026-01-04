<?php

namespace Domain\Promocode\Models\Accessors;

use Domain\Order\Models\Order;
use Domain\Promocode\Models\Promocode;

final class UsedCount
{
    public function __construct(
        private readonly Promocode $promocode,
    ) {
    }

    public function __invoke(): int
    {
        if (!$this->promocode->exists) {
            return 0;
        }

        $uuid = Order::query()
            ->where('promo_id', $this->promocode->id)
            ->whereNotCanceled()
            ->get()
            ->pluck('uuid')
            ->toArray();

        $uuid = array_unique($uuid);

        $uuid = array_unique($uuid);

        return count($uuid);
    }
}
