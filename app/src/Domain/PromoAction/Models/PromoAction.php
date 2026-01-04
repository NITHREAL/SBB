<?php

namespace Domain\PromoAction\Models;

use Domain\Image\Models\Attachment;
use Domain\Image\Traits\Attachable;
use Domain\Product\Models\Product;
use Domain\PromoAction\QueryBuilders\PromoActionQueryBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Infrastructure\Accessors\FormattedActiveFrom;
use Infrastructure\Accessors\FormattedActiveTo;
use Infrastructure\Eloquent\Traits\Active;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

/**
 * @method static PromoActionQueryBuilder query()
 */
class PromoAction extends Model
{
    use AdminFilterable;
    use AsSource;
    use Attachable;
    use HasFactory;
    use Active;

    protected $table = 'promo_actions';

    protected $fillable = [
        'title',
        'description',
        'short_description',
        'slug',
        'active_from',
        'active_to',
        'sort',
        'active',
    ];

    protected $casts = [
        'active_from' => 'date',
        'active_to'   => 'date',
        'sort'      => 'integer',
        'active'    => 'boolean',
    ];

    protected $allowedSorts = [
        'id',
        'title',
        'active_from',
        'active_to',
        'sort',
        'active',
    ];

    public function image(): BelongsTo
    {
        return $this->belongsTo(Attachment::class, 'image_id');
    }

    public function miniImage(): BelongsTo
    {
        return $this->belongsTo(Attachment::class, 'mini_image_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'promo_action_products',
            'promo_action_id',
            'product_id',
        )->withPivot(['sort']);
    }

    public function formattedActiveFrom(): Attribute
    {
        return Attribute::get(new FormattedActiveFrom($this));
    }

    public function formattedActiveTo(): Attribute
    {
        return Attribute::get(new FormattedActiveTo($this));
    }

    public function newEloquentBuilder($query): PromoActionQueryBuilder
    {
        return new PromoActionQueryBuilder($query);
    }
}
