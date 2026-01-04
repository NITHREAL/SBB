<?php

namespace Domain\Product\DTO\Product;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseCatalogParamsDTO;

class PopularProductParamsDTO extends BaseCatalogParamsDTO
{
    public function __construct(
        ?int $limit,
        array $sortBy,
    ) {
        parent::__construct($limit, $sortBy);
    }

    public static function make(array $data): self
    {
        return new static(
            Arr::get($data, 'limit'),
            Arr::get($data, 'sort', []),
        );
    }
}
