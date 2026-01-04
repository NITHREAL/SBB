<?php

namespace Domain\Image\Traits;

use Domain\Image\Models\Attachment;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

trait Attachable
{
    /**
     * @param string|null $group
     *
     * @return MorphToMany
     */
    public function attachment(string $group = null): MorphToMany
    {
        return $this->prepareQuery($group);
    }

    public function images(string $group = null): MorphToMany
    {
        return $this->prepareQuery($group)
            ->where('mime', 'like', 'image/%')
            ->where('active', true)
            ->orderBy('main', 'desc');
    }

    public function getImageFromAttachmentAttribute(string $group = null): ?Attachment
    {
        return $this->images($group)->where('main', 1)->first();
    }

    private function prepareQuery(string $group = null): MorphToMany
    {
        $query = $this->morphToMany(
            Attachment::class,
            'attachmentable',
            'attachmentable',
            'attachmentable_id',
            'attachment_id',
            'id',
            'id'
        );

        if ($group !== null) {
            $query->where('group', $group);
        }

        return $query->orderBy('sort');
    }

    public function getLazyLoadedImageAttribute(): Attachment|null
    {
        return $this->images
            ?->sortByDesc('id')
            ->first();
    }

    public function sync(Collection|array $ids, $detaching = true): self
    {
        if ($ids instanceof Collection) {
            $ids = $ids->pluck('id')->all();
        }

        $this->attachment()->sync($ids, $detaching);

        return $this;
    }

    public function certificates(): MorphToMany
    {
        return $this->images('certificates');
    }
}
