<?php

namespace Domain\Story\Models;

use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryMetadata extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_name',
        'phone',
        'card_number',
        'view_date',
        'view_duration',
        'was_clicked',
        'moved_to_next',
    ];

    protected $casts = [
        'was_clicked' => 'boolean',
        'moved_to_next' => 'boolean',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
