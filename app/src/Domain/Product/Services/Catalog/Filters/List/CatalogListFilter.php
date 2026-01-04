<?php

namespace Domain\Product\Services\Catalog\Filters\List;

use Domain\Product\Services\Catalog\Filters\CatalogFilter;

interface CatalogListFilter extends CatalogFilter
{
    public function getValues(): array;
}
