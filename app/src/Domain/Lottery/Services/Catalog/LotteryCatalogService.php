<?php

namespace Domain\Lottery\Services\Catalog;

use Domain\Image\Helpers\ImageUrlHelper;
use Domain\Image\Models\Attachment;
use Domain\Lottery\DTO\Catalog\LotteryCatalogFiltersDTO;
use Domain\Lottery\DTO\Catalog\LotteryCatalogParamsDTO;
use Domain\Product\Models\Product;
use Infrastructure\DTO\Catalog\CatalogParamsDTOInterface;
use Infrastructure\Services\Catalog\BaseCatalogPageService;

class LotteryCatalogService extends BaseCatalogPageService
{
    public function __construct(LotteryCatalogFilterService $filterService)
    {
        parent::__construct($filterService);
    }

    public function getLotteryCatalogData(
        LotteryCatalogParamsDTO $paramsDTO,
        LotteryCatalogFiltersDTO $filtersDTO,
        string $encodedRequestParams,
    ): array {
        $data = $this->getProductsAndFiltersData($paramsDTO, $filtersDTO, $encodedRequestParams);

        $data['lottery'] = $this->getPreparedLottery($paramsDTO->getLottery());

        return $data;
    }

    protected function setProductsQueryBuilder(CatalogParamsDTOInterface $paramsDTO): void
    {
        $this->queryBuilder = Product::query()
            ->smallProductCardsQuery(
                $paramsDTO->getStoreSystemId(),
            );
    }

    protected function setParamsConditions(CatalogParamsDTOInterface $paramsDTO): void
    {
        $lottery = $paramsDTO->getLottery();

        if ($lottery) {
            $this->queryBuilder->whereLottery($lottery->id);
        }
    }

    private function getPreparedLottery(object $lottery): object
    {
        $imageId = $lottery->image_id;

        if ($imageId && $image = Attachment::query()->find($imageId)) {
            $lottery->imageUrl = ImageUrlHelper::getUrl($image);
        }

        return $lottery;
    }
}
