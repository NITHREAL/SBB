<?php

namespace Domain\Product\Services\Catalog\Filters\Checkbox;

use Domain\Product\Services\Catalog\Filters\CatalogFilter;

interface CatalogCheckboxFilter extends CatalogFilter
{
    public function isAvailable(): bool;

    public function isSelected(): bool;
}
