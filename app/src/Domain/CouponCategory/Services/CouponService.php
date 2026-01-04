<?php

namespace Domain\CouponCategory\Services;

use Domain\CouponCategory\Models\AppliedCoupon;

class CouponService
{
    public function deleteCouponUse(int $orderId): void
    {
        AppliedCoupon::query()->where('order_id', $orderId)->delete();
    }
}
