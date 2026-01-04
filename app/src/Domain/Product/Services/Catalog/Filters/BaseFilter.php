<?php

namespace Domain\Product\Services\Catalog\Filters;

abstract class BaseFilter implements CatalogFilter
{
    protected string $title = 'Default';

    protected string $slug = 'default';

    protected string $displayType = 'default';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDisplayType(): string
    {
        return $this->displayType;
    }
}
