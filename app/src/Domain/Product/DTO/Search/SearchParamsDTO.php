<?php

namespace Domain\Product\DTO\Search;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseCatalogParamsDTO;

class SearchParamsDTO extends BaseCatalogParamsDTO
{
    private const DEFAULT_LIMIT = 20;

    private string $search;

    public function __construct(
        ?int $limit,
        array $sortBy,
        string $search,
    ) {
        parent::__construct($limit, $sortBy);

        $this->search = $search;
    }

    public static function make(array $data): self
    {
        return new static(
            Arr::get($data, 'limit') ?? self::DEFAULT_LIMIT,
            Arr::get($data, 'sort', []),
            Arr::get($data, 'search'),
        );
    }

    public function getSearch(): string
    {
        return $this->search;
    }
}
