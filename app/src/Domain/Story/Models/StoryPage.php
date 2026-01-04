<?php

declare(strict_types=1);

namespace Domain\Story\Models;

use Domain\Image\Traits\Attachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;

class StoryPage extends Model
{
    use AsSource;
    use HasFactory;
    use SoftDeletes;
    use Attachable;

    protected $fillable = [
        'title',
        'type',
        'text',
        'position',
        'type',
        'label',
        'label_color',
        'timer',
        'target_id',
        'story_id',
        'image_id',
        'target_url',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'label'         => 'string',
        'label_color'   => 'string',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }
}
