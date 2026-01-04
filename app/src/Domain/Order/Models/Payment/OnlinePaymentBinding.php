<?php

namespace Domain\Order\Models\Payment;

use Domain\Order\Models\Order;
use Domain\Order\QueryBuilders\OnlinePaymentBindingQueryBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *@method static OnlinePaymentBindingQueryBuilder query()
 */
class OnlinePaymentBinding extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'acquiring_binding_id',
        'card_description',
        'expiry_date',
        'acquiring_type',
        'is_default',
        'first_chars',
        'last_chars',
        'card_type',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OnlinePayment::class, 'binding_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'binding_id');
    }

    public function newEloquentBuilder($query): OnlinePaymentBindingQueryBuilder
    {
        return new OnlinePaymentBindingQueryBuilder($query);
    }
}
