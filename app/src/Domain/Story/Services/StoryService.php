<?php

namespace Domain\Story\Services;

use Domain\Image\Helpers\ImagePropertiesHelper;
use Domain\Image\Models\Attachment;
use Domain\Product\Models\Product;
use Domain\Story\DTO\PreparedStoryDTO;
use Domain\Story\Enums\StoryPageTypeEnum;
use Domain\Story\Models\Story;
use Domain\Story\Models\StoryMetadata;
use Domain\Story\Models\StoryPage;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Infrastructure\Services\Buyer\Facades\BuyerStore;

class StoryService
{
    private const DEFAULT_LIMIT = 20;

    public function __construct(
        private readonly StoryPagesService $storyPagesService,
    ) {
    }

    public function getList(?int $userId, ?int $limit): Collection
    {
        $limit = $limit ?? self::DEFAULT_LIMIT;

        $stories = Story::query()
            ->baseQuery()
            ->active()
            ->user($userId)
            ->limit($limit)
            ->get();

        return $this->getPreparedStoriesCollection($stories, $userId);
    }

    public function getForGroup(int $id, ?int $userId): ?object
    {
        /** @var Story $story */
        $story =  Story::query()
            ->baseQuery()
            ->where('stories.id', $id)
            ->active()
            ->whereAvailableInGroups()
            ->user($userId, $id)
            ->first();

        if ($story) {
            $storyPages = $story->pages;

            $imageIds = array_merge(
                [$story->image_Id],
                $storyPages->pluck('image_id')->toArray(),
            );

            $images = $this->getImages($imageIds);

            // Товары для страниц историй с типом "Товар".
            // Необходимо чтобы определить доступен ли товар чтобы показывать или скрывать диплинк для перехода в карточку товара
            $products = $this->getStoryPageProducts($storyPages);

            $metaData = $userId
                ? $this->getMetadata([$story->id], $userId)
                : collect();

            $preparedStoryDTO = PreparedStoryDTO::make(
                $storyPages,
                $images,
                $metaData,
                $products,
                $userId,
            );

            $story = $this->getPreparedStory($story, $preparedStoryDTO);
        }

        return $story;
    }

    private function getPreparedStoriesCollection(Collection $stories, ?int $userId): Collection
    {
        $storyPages = StoryPage::query()
            ->whereIn('story_pages.story_id', $stories->pluck('id')->toArray())
            ->get();

        $imageIds = array_merge(
            $stories->pluck('image_id')->toArray(),
            $storyPages->pluck('image_id')->toArray(),
        );

        // Товары для страниц историй с типом "Товар".
        // Необходимо чтобы определить доступен ли товар чтобы показывать или скрывать диплинк для перехода в карточку товара
        $products = $this->getStoryPageProducts($storyPages);

        $metaData = $userId
            ? $this->getMetadata(
                $stories
                    ->pluck('id')
                    ->toArray(),
                $userId)
            : collect();

        $images = $this->getImages($imageIds);

        $preparedStoryDTO = PreparedStoryDTO::make(
            $storyPages,
            $images,
            $metaData,
            $products,
            $userId,
        );

        return $stories->map(fn (Story $item) => $this->getPreparedStory($item, $preparedStoryDTO));
    }

    private function getPreparedStory(
        Story $story,
        PreparedStoryDTO $preparedStoryDTO
    ): object {
        $storyImage = $preparedStoryDTO
            ->getImages()
            ->where('id', $story->image_id)
            ->first();

        if ($storyImage) {
            $story = $this->setImage($story, $storyImage);
        }

        $story->setAttribute(
            'pages',
            $this->storyPagesService->getStoryPagesCollection(
                $preparedStoryDTO
                    ->getStoryPages()
                    ->where('story_id', $story->id),
                $preparedStoryDTO->getImages(),
                $preparedStoryDTO->getProducts(),
            ),
        );

        $story->setAttribute(
            'isWatched',
            $this->getIsWatched(
                $preparedStoryDTO->getUserId(),
                $story,
                $preparedStoryDTO->getMetaData(),
            ),
        );

        return $story;
    }

    private function getStoryPageProducts(Collection $storyPages): Collection
    {
        $store1cSlug = BuyerStore::getOneCId();

        $productSlugs = $storyPages
            ->where('type', StoryPageTypeEnum::product()->value)
            ->map(function (object $page) {
                $page->productSlug = Str::afterLast($page->target_id, '/');

                return $page;
            })
            ->pluck('productSlug')
            ->toArray();

        return Product::query()
            ->baseQuery()
            ->whereIn('products.slug', $productSlugs)
            ->whereStoreOneCId($store1cSlug)
            ->get();
    }

    private function getMetadata(array $storyIds, int $userId): Collection
    {
        return StoryMetadata::query()
            ->whereIn('story_id', $storyIds)
            ->where('user_id', $userId)
            ->get();
    }

    private function getImages(array $imageIds): Collection
    {
        return Attachment::query()
            ->whereIn('id', $imageIds)
            ->get();
    }

    private function setImage(object $object, Attachment $image): object
    {
        return ImagePropertiesHelper::setImageProperties($object, $image);
    }

    private function getIsWatched(
        ?int $userId,
        Story $story,
        Collection $metaData
    ): bool {
        return !is_null($userId) && !is_null(
                $metaData
                    ->where('story_id', $story->id)
                    ->where('user_id', $userId)
                    ->first()
            );
    }
}
