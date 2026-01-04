<?php

namespace Domain\CouponCategory\Models;

use Illuminate\Database\Eloquent\Model;

class AppliedCoupon extends Model
{
    protected $fillable = [
        'order_id',
        'copon_number',
    ];
}
