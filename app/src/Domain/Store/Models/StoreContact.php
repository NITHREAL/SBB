<?php

namespace Domain\Store\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'value'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
