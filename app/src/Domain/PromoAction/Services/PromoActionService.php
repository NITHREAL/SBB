<?php

namespace Domain\PromoAction\Services;

use Domain\PromoAction\DTO\PromoActionCollectionDTO;
use Domain\PromoAction\DTO\PromoActionOneDTO;
use Illuminate\Support\Collection;

readonly class PromoActionService
{
    public function __construct(
        private PromoActionSelectService $promoActionSelectService,
        private PromoActionProductsService $promoActionProductsService,
    ) {
    }

    public function getPromoActions(PromoActionCollectionDTO $promoActionCollectionDTO): Collection
    {
        $promoActions = $this->promoActionSelectService->getPromoActions($promoActionCollectionDTO->getLimit());

        if ($promoActionCollectionDTO->isWithProducts()) {
            $promoActions = $this->setProductsToPromoActions($promoActions, $promoActionCollectionDTO->getProductsLimit());
        }

        return $promoActions;
    }

    public function getOnePromoAction(PromoActionOneDTO $promoActionOneDTO): object
    {
        $promoAction = $this->promoActionSelectService->getOnePromoAction($promoActionOneDTO->getSlug());

        $products = $this->promoActionProductsService->getProductsForPromoActions(
            [$promoAction->id],
            $promoActionOneDTO->getProductsLimit(),
        );

        return $this->setProductsToPromoAction($promoAction, $products);
    }

    private function setProductsToPromoActions(Collection $promoActions, int $productsLimit): Collection
    {
        $products = $this->promoActionProductsService->getProductsForPromoActions(
            $promoActions->pluck('id')->toArray(),
            $productsLimit,
        );

        return $promoActions->map(fn(object $promoAction) => $this->setProductsToPromoAction($promoAction, $products));
    }

    private function setProductsToPromoAction(object $promoAction, Collection $products): object
    {
        $products = $products->where('promo_action_id', $promoAction->id)->sortBy('sort');

        $promoAction->setAttribute(
            'productsData',
            $products,
        );

        return $promoAction;
    }
}
