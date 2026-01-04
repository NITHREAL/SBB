<?php

namespace Domain\Story\Services;

use Domain\Image\Helpers\ImagePropertiesHelper;
use Domain\Story\Enums\StoryPageTypeEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class StoryPagesService
{
    public function getStoryPagesCollection(
        Collection $pages,
        Collection $images,
        Collection $products,
    ): Collection {
        return $pages
            ->map(function ($item) use ($images, $products) {
                $image = $images->where('id', $item->image_id)->first();

                $item = $this->setPageTargetProperties($item, $products);

                if ($image) {
                    $item = ImagePropertiesHelper::setImageProperties($item, $image);
                }

                return $item;
            });
    }

    private function setPageTargetProperties(object $page, Collection $products): object
    {
        if ($page->type === StoryPageTypeEnum::product()->value) {
            $product = $this->getStoryPageProduct($page, $products);

            if (empty($product)) {
                $page->target_id = null;
                $page->target_url = null;
            }
        }

        return $page;
    }

    private function getStoryPageProduct(object $page, Collection $products): ?object
    {
        $productSlug = Str::afterLast($page->target_id, '/');

        // Если товар недоступен к просмотру, то диплинк в истории выводить не нужно
        return $products->where('slug', $productSlug)->first();
    }
}
