<?php

namespace Domain\MetaTag\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MetaTagValues extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'keywords',
        'header_one',
        'header_type'
    ];

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }
}
