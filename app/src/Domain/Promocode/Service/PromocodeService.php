<?php

namespace Domain\Promocode\Service;

use Domain\Order\Models\Order;

class PromocodeService
{
    public function getUsedCountByUser(int $promocodeId, int $userId = null): int
    {
        $uuids = Order::query()
            ->whereNotCanceled()
            ->when($userId, fn ($query) => $query->whereUser($userId))
            ->wherePromocode($promocodeId)
            ->get()
            ->pluck('uuid')
            ->toArray();

        return count(array_unique($uuids));
    }
}
