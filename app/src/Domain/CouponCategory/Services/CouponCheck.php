<?php

namespace Domain\CouponCategory\Services;

use Domain\CouponCategory\Models\AppliedCoupon;
use Domain\CouponCategory\Models\CouponCategory;
use Domain\CouponCategory\Services\Exceptions\CouponException;

class CouponCheck
{
    public function __construct(
        private CouponCategory $coupon,
    ) {
    }

    public function createCouponUse(int $orderId): void
    {
        $appliedCoupon = new AppliedCoupon();
        $appliedCoupon->order_id = $orderId;
        $appliedCoupon->coupon_number = $this->coupon->category_coupon_guid;
        $appliedCoupon->save();
    }

    /**
     * @throws CouponException
     */
    public function checkCouponAvailability(): void
    {
        if (empty($this->coupon->minimum_amount)) {
            throw new CouponException(
                "Для категории купонов {$this->coupon->name} в админке не заполено поле Минимальная сумма заказа",
                400,
            );
        }

        if (empty($this->coupon->amount_discount)) {
            throw new CouponException(
                "Для категории купонов {$this->coupon->name} в админке не заполено поле Размер скидки, р",
                400,
            );
        }
    }

    /**
     * @throws CouponException
     */
    public function checkMinimumAmount(float $basketTotal): void
    {
        $couponMinimumAmount = $this->coupon->minimum_amount;

        if (!empty($couponMinimumAmount) && $couponMinimumAmount > $basketTotal) {
            throw new CouponException(
                "Для использования купона сумма заказа должна быть не менее {$couponMinimumAmount} руб",
                400,
            );
        }
    }
}
