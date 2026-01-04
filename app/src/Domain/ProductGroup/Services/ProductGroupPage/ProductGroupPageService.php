<?php

namespace Domain\ProductGroup\Services\ProductGroupPage;

use Domain\Image\Helpers\ImageUrlHelper;
use Domain\Image\Models\Attachment;
use Domain\Product\Models\Product;
use Domain\ProductGroup\DTO\ProductGroupPage\ProductGroupPageFiltersDTO;
use Domain\ProductGroup\DTO\ProductGroupPage\ProductGroupPageParamsDTO;
use Domain\ProductGroup\Models\ProductGroup;
use Domain\Story\Services\StoryService;
use Infrastructure\DTO\Catalog\CatalogFiltersDTOInterface;
use Infrastructure\DTO\Catalog\CatalogParamsDTOInterface;
use Infrastructure\Services\Catalog\BaseCatalogPageService;

class ProductGroupPageService extends BaseCatalogPageService
{
    protected string $filtersCacheKeyPrefix = 'product_group_catalog_filters_list';

    private StoryService $storyService;

    public function __construct(
        ProductGroupPageFilterService $filterService,
        StoryService $storyService,
    ) {
        parent::__construct($filterService);

        $this->storyService = $storyService;
    }

    public function getProductGroupPageData(
        ProductGroupPageParamsDTO $paramsDTO,
        ProductGroupPageFiltersDTO $filtersDTO,
        string $encodedRequestData,
    ): array {
        $data = $this->getProductsAndFiltersData($paramsDTO, $filtersDTO, $encodedRequestData);

        $data['productGroup'] = $this->getPreparedProductGroupData($paramsDTO->getProductGroup(), $paramsDTO->getUserId());

        return $data;
    }

    protected function setProductsQueryBuilder(CatalogParamsDTOInterface $paramsDTO): void
    {
        $this->queryBuilder = Product::query()
            ->groupsQuery([$paramsDTO->getProductGroup()->id], $paramsDTO->getStoreSystemId());
    }

    protected function setParamsConditions(CatalogParamsDTOInterface $paramsDTO): void
    {

    }

    protected function setFiltersConditions(CatalogFiltersDTOInterface $filtersDTO): void
    {
        if ($filtersDTO->isNeedAvailableToday()) {
            $this->queryBuilder->whereAvailableToday();
        }

        if ($filtersDTO->isNeedForVegan()) {
            $this->queryBuilder->whereForVegan();
        }

        if ($filtersDTO->getFarmers()) {
            $this->queryBuilder->whereFarmers($filtersDTO->getFarmers());
        }
    }

    private function getPreparedProductGroupData(ProductGroup $productGroup, ?int $userId): array
    {
        $image = Attachment::query()
            ->baseQuery()
            ->whereOwners(
                [$productGroup->id],
                'group',
            )
            ->first();

        return [
            'id'                => $productGroup->id,
            'title'             => $productGroup->title,
            'slug'              => $productGroup->slug,
            'imageOriginal'     => $image ? ImageUrlHelper::getUrl($image) : null,
            'imageBlurHash'     => $image?->blur_hash,
            'backgroundImage'   => $productGroup->backgroundImage
                ? ImageUrlHelper::getUrl($productGroup->backgroundImage)
                : null,
            'storyData'         => $productGroup->story_id
                ? $this->storyService->getForGroup($productGroup->story_id, $userId)
                : null,
        ];
    }
}
