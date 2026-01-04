<?php

namespace Domain\Order\Models\Delivery;

use Database\Factories\PolygonTypeFactory;
use Domain\Order\QueryBuilders\PolygonTypeQueryBuilder;
use Domain\Store\Models\StoreScheduleDate;
use Domain\Store\Models\StoreScheduleWeekday;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

/**
 * @method static PolygonTypeQueryBuilder query()
 */
class PolygonType extends Model
{
    use HasFactory;
    use AsSource;
    use AdminFilterable;


    protected $fillable = [
        'type',
        'delivery_type',
        'title',
        'description',
        'tooltip',
    ];

    protected $allowedSorts = [
        'id',
        'type',
        'delivery_type',
        'title',
        'description',
        'tooltip',
    ];

    public function scheduleDates(): HasMany
    {
        return $this->hasMany(StoreScheduleDate::class);
    }

    public function scheduleWeekdays(): HasMany
    {
        return $this->hasMany(StoreScheduleWeekday::class);
    }

    public function newEloquentBuilder($query): PolygonTypeQueryBuilder
    {
        return new PolygonTypeQueryBuilder($query);
    }

    public static function getPickupTypes(): Collection
    {
        return self::query()
            ->wherePickup()
            ->get()
            ->keyBy('type');
    }

    protected static function newFactory(): Factory
    {
        return PolygonTypeFactory::new();
    }
}
