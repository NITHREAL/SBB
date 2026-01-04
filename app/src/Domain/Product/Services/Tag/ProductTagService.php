<?php

namespace Domain\Product\Services\Tag;

use Domain\ProductGroup\Services\ProductGroupSelection;
use Domain\Tag\Service\TagSelectionService;
use Illuminate\Support\Collection;

class ProductTagService
{
    public function setTagsToProductsCollection(Collection $products): Collection
    {
        $groups = ProductGroupSelection::getProductGroupsByProductIds(
            $products->pluck('id')->toArray()
        );

        $tags = TagSelectionService::getTagByGroups(
            $groups->pluck('groupId')->toArray(),
        );

        return $products->map(function (object $product) use ($groups, $tags) {
            return $this->setGroupTagsToProduct($product, $groups, $tags);
        });
    }

    public function setTagsToOneProduct(object $product): object
    {
        $groups = ProductGroupSelection::getProductGroupsByProductIds(
            [$product->id],
        );

        $tags = TagSelectionService::getTagByGroups(
            $groups->pluck('groupId')->toArray(),
        );

        return $this->setGroupTagsToProduct($product, $groups, $tags);
    }

    private function setGroupTagsToProduct(
        object $product,
        Collection $groups,
        Collection $tags,
    ): object {
        $tagsData = $product->tagsData ?? [];

        $group = $groups->where('productId', $product->id)->sortBy('sort')->first();

        if ($group) {
            $tags = $tags->where('groupId', $group->groupId);

            foreach ($tags as $tag) {
                if (!empty($tag) && ($group->active || $tag->show_forced)) {
                    $tagsData[] = [
                        'text'      => $tag->text,
                        'color'     => $tag->color,
                        'slug'      => $group->active
                            ? $group->slug
                            : null,
                    ];
                }
            }
        }

        $product->tagsData = $tagsData;

        return $product;
    }
}
