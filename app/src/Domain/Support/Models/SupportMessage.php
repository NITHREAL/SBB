<?php

declare(strict_types=1);

namespace Domain\Support\Models;

use Domain\Support\Models\Accessors\SupportMessage\PreparedAuthor;
use Domain\Support\QueryBuilders\SupportMessageQueryBuilder;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Orchid\Screen\AsSource;

/**
 * @method static SupportMessageQueryBuilder query()
 */
class SupportMessage extends Model
{
    use HasFactory;
    use AsSource;
    use Notifiable;

    protected $fillable = [
        'text',
        'viewed',
        'author',
        'stuff_only',
        'user_id',
    ];

    protected $attributes = [
        'viewed' => false,
    ];

    protected $casts = [
        'viewed'        => 'boolean',
        'stuff_only'    => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function preparedAuthor(): Attribute
    {
        return new Attribute(new PreparedAuthor($this));
    }

    public function newEloquentBuilder($query): SupportMessageQueryBuilder
    {
        return new SupportMessageQueryBuilder($query);
    }
}
