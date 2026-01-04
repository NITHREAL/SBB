<?php

namespace Domain\Order\Models;

use Database\Factories\OrderFactory;
use Domain\Order\Models\Accessors\IsCanceled;
use Domain\Order\Models\Accessors\IsCompleted;
use Domain\Order\Models\Accessors\IsRepeatable;
use Domain\Order\Models\Accessors\IsSupportable;
use Domain\Order\Models\Accessors\PreparedDeliverySubType;
use Domain\Order\Models\Delivery\PolygonDeliveryPrice;
use Domain\Order\Models\Exchange\OrderExchangeLog;
use Domain\Order\Models\Payment\OnlinePayment;
use Domain\Order\Models\Payment\OnlinePaymentBinding;
use Domain\Order\Models\Payment\PaymentType;
use Domain\Order\Models\Traits\OnlinePayments;
use Domain\Order\QueryBuilders\OrderQueryBuilder;
use Domain\Product\Models\Product;
use Domain\Promocode\Models\Promocode;
use Domain\Store\Models\Store;
use Domain\User\Models\User;
use Domain\UtmLabel\Models\UtmLabel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

/**
 *@method static OrderQueryBuilder query()
 */
class Order extends Model
{
    use HasFactory;
    use AsSource;
    use AdminFilterable;
    use OnlinePayments;

    protected $fillable = [
        'system_id',
        'uuid',
        'store_system_id',
        'discount',
        'payment_type',
        'delivery_type',
        'delivery_sub_type',
        'delivery_service',
        'status',
        'bill',
        'comment',
        'delivery_cost',
        'receive_date',
        'receive_interval',
        'need_exchange',
        'need_receipt',
        'amount_bonus',
        'exported_at',
        'completed_at',
        'sm_original_order_id',
        'sm_status',
        'delivery_price_in_polygon_id',
        'request_from',
        'total_price',
        'batch',
        'payer_ip',
    ];

    protected $casts = [
        'need_receipt' => 'bool',
        'completed_at' => 'datetime',
    ];

    protected $allowedSorts = [
        'id',
        'created_at',
        'request_from',
    ];

    public function deliveryPriceInPolygon(): BelongsTo
    {
        return $this->belongsTo(PolygonDeliveryPrice::class, 'delivery_price_in_polygon_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_system_id', 'system_id');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type', 'code');
    }

    public function utm(): MorphToMany
    {
        return $this->morphToMany(
            UtmLabel::class,
            'labeled',
            'utm_labelable'
        );
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'order_product',
            'order_id',
            'product_system_id',
            'id',
            'system_id',
        )->withPivot([
            'unit_system_id',
            'replacement_system_id',
            'status',
            'price',
            'price_discount',
            'price_buy',
            'count',
            'is_discount',
            'original_quantity',
            'collected_quantity',
            'weight',
            'total',
            'total_without_discount',
            'id as itemId'
        ])->withoutGlobalScopes();
    }

    public function contacts(): HasOne
    {
        return $this->hasOne(OrderContact::class);
    }

    public function binding(): BelongsTo
    {
        return $this->belongsTo(OnlinePaymentBinding::class);
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(
            OnlinePayment::class,
            'order_payment',
            'order_id',
            'payment_id',
        )->withPivot(['amount']);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type', 'code');
    }

    public function promocode(): BelongsTo
    {
        return $this->belongsTo(Promocode::class, 'promo_id');
    }

    public function exchangeLogs(): HasMany
    {
        return $this->hasMany(OrderExchangeLog::class);
    }

    public function externalCheck(): HasOne
    {
        return $this->hasOne(ExternalCheck::class);
    }

    public function preparedDeliverySubType(): Attribute
    {
        return Attribute::get(new PreparedDeliverySubType($this));
    }

    public function isCompleted(): Attribute
    {
        return Attribute::get(new IsCompleted($this));
    }

    public function isCanceled(): Attribute
    {
        return Attribute::get(new IsCanceled($this));
    }

    public function isSupportable(): Attribute
    {
        return Attribute::get(new IsSupportable($this));
    }

    public function isRepeatable(): Attribute
    {
        return Attribute::get(new IsRepeatable($this));
    }

    public function newEloquentBuilder($query): OrderQueryBuilder
    {
        return new OrderQueryBuilder($query);
    }

    public function exchangeLastLog(): HasOne
    {
        return $this->hasOne(OrderExchangeLog::class)->orderBy('id', 'desc');
    }

    protected static function newFactory(): Factory
    {
        return OrderFactory::new();
    }

    public function settings(): HasOne
    {
        return $this->hasOne(OrderSetting::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(OrderReview::class);
    }
}
