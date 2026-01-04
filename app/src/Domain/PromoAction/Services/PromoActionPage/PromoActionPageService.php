<?php

namespace Domain\PromoAction\Services\PromoActionPage;

use Domain\Image\Helpers\ImageUrlHelper;
use Domain\Image\Models\Attachment;
use Domain\Product\Models\Product;
use Domain\PromoAction\DTO\PromoActionPage\PromoActionPageFiltersDTO;
use Domain\PromoAction\DTO\PromoActionPage\PromoActionPageParamsDTO;
use Infrastructure\DTO\Catalog\CatalogFiltersDTOInterface;
use Infrastructure\DTO\Catalog\CatalogParamsDTOInterface;
use Infrastructure\Services\Catalog\BaseCatalogPageService;

class PromoActionPageService extends BaseCatalogPageService
{
    public function __construct(PromoActionPageFilterService $filterService)
    {
        parent::__construct($filterService);
    }

    public function getPromoActionPageData(
        PromoActionPageParamsDTO  $paramsDTO,
        PromoActionPageFiltersDTO $filtersDTO,
        string $encodedRequestParams,
    ): array {
        $data = $this->getProductsAndFiltersData($paramsDTO, $filtersDTO, $encodedRequestParams);

        $data['promoAction'] = $this->getPreparedPromoAction($paramsDTO->getPromoAction());

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
        if ($paramsDTO->getPromoAction()) {
            $this->queryBuilder->wherePromoAction($paramsDTO->getPromoAction()->id);
        }
    }

    private function getPreparedPromoAction(object $promoAction): object
    {
        $imageId = $promoAction->image_id;

        if ($imageId && $image = Attachment::query()->find($imageId)) {
            $promoAction->imageUrl = ImageUrlHelper::getUrl($image);
        }

        return $promoAction;
    }
}
