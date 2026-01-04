<?php

namespace Domain\MobileToken\Models;

use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileAppToken extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token',
        'service',
        'device',
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $casts = [
        'token'     => 'string',
        'service'   => 'string',
        'device'    => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
