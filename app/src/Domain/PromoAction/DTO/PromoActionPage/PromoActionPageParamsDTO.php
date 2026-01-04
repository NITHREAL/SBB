<?php

namespace Domain\PromoAction\DTO\PromoActionPage;

use Domain\PromoAction\Models\PromoAction;
use Domain\PromoAction\Services\PromoActionSelection;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseCatalogParamsDTO;

class PromoActionPageParamsDTO extends BaseCatalogParamsDTO
{
    private PromoAction $promoAction;

    public function __construct(
        ?int $limit,
        array $sortBy,
        PromoAction $promoAction,
    ) {
        parent::__construct($limit, $sortBy);

        $this->promoAction = $promoAction;
    }

    public static function make(array $data, string $slug): self
    {
        $promoAction = PromoActionSelection::getPromoActionBySlug($slug);

        return new self(
            Arr::get($data, 'limit'),
            Arr::get($data, 'sort', []),
            $promoAction,
        );
    }

    public function getPromoAction(): object
    {
        return $this->promoAction;
    }
}
