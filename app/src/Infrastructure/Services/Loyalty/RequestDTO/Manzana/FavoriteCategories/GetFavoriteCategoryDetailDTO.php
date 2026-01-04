<?php

namespace Infrastructure\Services\Loyalty\RequestDTO\Manzana\FavoriteCategories;

use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\RequestDTO\BaseDTO;

class GetFavoriteCategoryDetailDTO extends BaseDTO
{
    public function __construct(
        private readonly string $sessionId,
        private readonly string $productListGroupId,
    ) {
    }

    public static function make(array $data): self
    {
        return new self(
            Arr::get($data, 'sessionId'),
            Arr::get($data, 'productListGroupId'),
        );
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getProductListGroupId(): string
    {
        return $this->productListGroupId;
    }
}
