<?php

namespace Domain\Product\Services\Catalog\Filters;

interface CatalogFilter
{
    public function getTitle(): string;

    public function getSlug(): string;

    public function getDisplayType(): string;

    public function getFilterData(): array;
}
