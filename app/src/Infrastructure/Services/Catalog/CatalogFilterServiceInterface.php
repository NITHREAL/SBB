<?php

namespace Infrastructure\Services\Catalog;

use Illuminate\Database\Eloquent\Builder;
use Infrastructure\DTO\Catalog\CatalogFiltersDTOInterface;

interface CatalogFilterServiceInterface
{
    public function getFilters(CatalogFiltersDTOInterface $filtersDTO, Builder $queryBuilder): array;
}
