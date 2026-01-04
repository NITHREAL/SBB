<?php

declare(strict_types=1);

namespace Domain\ProductGroup\Services;

use Domain\Image\Helpers\ImageUrlHelper;
use Domain\Image\Models\Attachment;
use Domain\Product\Models\Product;
use Domain\Product\Services\ProductCollectionService;
use Domain\ProductGroup\DTO\ProductGroupDTO;
use Domain\ProductGroup\Models\ProductGroup;
use Domain\ProductGroup\Resources\ProductGroupResource;
use Domain\ProductGroup\Resources\ProductGroupWithProductResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

readonly class ProductGroupService
{
    public function __construct(
        private ProductCollectionService $productCollectionService
    ) {
    }

    public function getGroups(ProductGroupDTO $productGroupDTO)
    {
        $groups = ProductGroup::query()
            ->baseQuery()
            ->when($productGroupDTO->getProducts(), fn(Builder $q) => $q->whereHas('products'))
            ->user($productGroupDTO->getUserId())
            ->whereActive()
            ->get();

        if ($productGroupDTO->getProducts()) {
            $products = Product::query()
                ->groupsQuery(
                    $groups->pluck('id')->toArray(),
                    $productGroupDTO->getStore1cId(),
                )
                ->get();

            $products = $this->productCollectionService->getPreparedProductsCollection($products);

            return ProductGroupWithProductResource::collection(
                $this->getPreparedGroupsCollection($groups, $products, $productGroupDTO->getProductsLimit())
            );
        }

        return ProductGroupResource::collection($this->getPreparedGroupsWithoutProductsCollection($groups));
    }

    private function getPreparedGroupsCollection(
        Collection $groups,
        Collection $products,
        int $productsLimit,
    ): Collection {
        $images = Attachment::query()
            ->baseQuery()
            ->whereOwners(
                $groups->pluck('id')->toArray(),
                'group',
            )
            ->get();

        return $groups->map(function ($item) use ($products, $images, $productsLimit) {
            $image = $images->where('owner_id', $item->id)->first();

            $item->groupProducts = $products->where('groupId', $item->id)->take($productsLimit);
            $item->image_original = $image ? ImageUrlHelper::getUrl($image) : null;
            $item->image_blur_hash = $image?->blur_hash;

            return $item;
        });
    }

    private function getPreparedGroupsWithoutProductsCollection(
        Collection $groups
    ): Collection {
        $images = Attachment::query()
            ->baseQuery()
            ->whereOwners(
                $groups->pluck('id')->toArray(),
                'group',
            )
            ->get();

        return $groups->map(function ($item) use ($images) {
            $image = $images->where('owner_id', $item->id)->first();

            $item->image_original = $image ? ImageUrlHelper::getUrl($image) : null;
            $item->image_blur_hash = $image?->blur_hash;

            return $item;
        });
    }
}
