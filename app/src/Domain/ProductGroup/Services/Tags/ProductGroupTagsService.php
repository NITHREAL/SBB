<?php

namespace Domain\ProductGroup\Services\Tags;

use Domain\ProductGroup\Models\ProductGroup;
use Domain\Tag\Models\Tag;
use Illuminate\Support\Arr;

class ProductGroupTagsService
{
    public function updateProductGroupTagsRelation(ProductGroup $group, array $tagsData): void
    {
        $tagIds = [];

        foreach ($tagsData as $tagData) {
            $tag = $this->getPreparedTag($tagData);

            $tagIds[] = $tag->id;
        }

        $group->tags()->sync($tagIds);
    }

    private function getPreparedTag(array $tagData): Tag
    {
        $id = Arr::get($tagData, 'id');

        $tag = Tag::find($id);

        if (empty($tag)) {
            $tag = new Tag();
        }

        $tag->fill([
            'text'          => Arr::get($tagData, 'text'),
            'color'         => Arr::get($tagData, 'color'),
            'active'        => Arr::get($tagData, 'active'),
            'show_forced'   => Arr::get($tagData, 'show_forced'),
        ]);

        $tag->save();

        return $tag;
    }
}
