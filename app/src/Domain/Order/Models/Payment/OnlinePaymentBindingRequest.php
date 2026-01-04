<?php

namespace Domain\Order\Models\Payment;

use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnlinePaymentBindingRequest extends Model
{
    protected $fillable = [
        'amount',
        'expires_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(OnlinePayment::class, 'payment_id');
    }
}
