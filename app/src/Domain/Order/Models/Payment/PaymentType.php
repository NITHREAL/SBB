<?php

namespace Domain\Order\Models\Payment;

use Domain\City\Models\City;
use Domain\Store\Models\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Infrastructure\Eloquent\Traits\Active;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable as AdminFilterable;

class PaymentType extends Model
{
    use HasFactory;
    use AsSource;
    use AdminFilterable;
    use Active;

    protected $table = 'payments';

    protected $fillable = [
        'active',
        'title',
        'code',
        'delivery_type',
        'sort',
        'for_all_cities'
    ];

    protected $casts = [
        'delivery_type' => 'array',
        'for_all_cities' => 'boolean'
    ];

    protected $allowedSorts = [
        'id',
        'active',
        'title',
        'code',
        'delivery_type',
        'sort'
    ];

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(
            City::class,
            'payment_city',
            'payment_id',
            'city_id',
        );
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(
            Store::class,
            'payment_store',
            'payment_id',
            'store_id'
        );
    }
}
