<?php

namespace Domain\Order\Models\Payment;

use Domain\Order\Enums\Payment\PaymentStatusEnum;
use Domain\Order\Models\Order;
use Domain\Order\Models\Traits\SberbankPaymentMethods;
use Domain\Order\QueryBuilders\OnlinePaymentQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;

/**
 * @method static OnlinePaymentQueryBuilder query()
 */
class OnlinePayment extends Model
{
    use SberbankPaymentMethods;
    use AsSource;
    use SoftDeletes;

    protected $fillable = [
        'entity_id',
        'sber_order_id',
        'status',
        'payed',
        'amount',
        'value',
        'form_url',
    ];

    public function orders(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Order::class,
                'order_payment',
                'payment_id',
                'order_id'
            )
            ->withPivot('amount');
    }

    public function bindingRequest(): HasOne
    {
        return $this->hasOne(OnlinePaymentBindingRequest::class, 'payment_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(
            OnlinePaymentLog::class,
            'online_payment_id',
            'id'
        );
    }

    public function scopeHeld(Builder $query): Builder
    {
        return $query->where('online_payments.status', PaymentStatusEnum::hold()->value);
    }

    public function scopeRegistered(Builder $query): Builder
    {
        return $query->where('online_payments.status', PaymentStatusEnum::registered()->value);
    }

    public function scopePayed(Builder $query): Builder
    {
        return $this
            ->where('online_payments.payed', true)
            ->whereIn(
                'status',
                [
                    PaymentStatusEnum::hold()->value,
                    PaymentStatusEnum::deposit()->value,
                ],
            );
    }

    public function newEloquentBuilder($query): OnlinePaymentQueryBuilder
    {
        return new OnlinePaymentQueryBuilder($query);
    }
}
