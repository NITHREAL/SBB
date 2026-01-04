<?php

namespace Domain\Order\Models;

use Domain\Image\Traits\Attachable;
use Domain\Store\Models\Store;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

class ExternalCheck extends Model
{
    use AsSource;
    use Attachable;
    use AdminFilterable;
    use HasFactory;

    protected $primaryKey = 'uid_purchase';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'uid_purchase',
        'order_id',
        'data_check',
    ];

    protected $casts = [
        'data_check' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
