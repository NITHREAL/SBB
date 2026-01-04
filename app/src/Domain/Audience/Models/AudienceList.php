<?php

namespace Domain\Audience\Models;

use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudienceList extends Model
{
    public $attributes = [
        'audience_id',
        'user_id',
    ];

    public $casts = [
        'audience_id' => 'integer',
        'user_id'=> 'integer',
    ];

    protected $table = 'audience_list';
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class);
    }
}
