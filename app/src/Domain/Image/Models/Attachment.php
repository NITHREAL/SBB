<?php

namespace Domain\Image\Models;

use Domain\Image\Models\Accessors\StreamableUrl;
use Domain\Image\QueryBuilders\ImageQueryBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Orchid\Attachment\Models\Attachment as OrchidAttachment;

/**
 * @method static ImageQueryBuilder query()
 */
class Attachment extends OrchidAttachment
{
    protected $fillable = [
        'system_id',
        'active',
        'main',
        'name',
        'original_name',
        'mime',
        'extension',
        'size',
        'path',
        'user_id',
        'description',
        'alt',
        'sort',
        'hash',
        'blur_hash',
        'disk',
        'group',
    ];

    protected $casts = [
        'active'    => 'boolean',
        'main'      => 'boolean',
        'sort'      => 'integer'
    ];

    public function physicalPath(): ?string
    {
        if ($this->disk === 'cdn') {
            $storage = Storage::disk('s3');
            $newName = pathinfo($this->original_name, PATHINFO_FILENAME) . '.webp';
            if($storage->exists($newName)) {
                return 'https://927907.selcdn.ru/catalog/' . $storage->path($newName);
            }

            return $this->original_name;
        }

        return parent::physicalPath();
    }

    public function stream($defaultPath = null): Attribute
    {
        return Attribute::get(new StreamableUrl($this, $defaultPath));
    }

    public function newEloquentBuilder($query): ImageQueryBuilder
    {
        return new ImageQueryBuilder($query);
    }
}
