<?php

namespace Domain\Product\Services\Catalog\Filters\Checkbox;

use Domain\Product\Enums\Catalog\CatalogFilterTypeEnum;
use Domain\Product\Services\Catalog\Filters\BaseFilter;

abstract class BaseCheckboxFilter extends BaseFilter implements CatalogCheckboxFilter
{
    protected string $title = 'Checkbox default';

    protected string $slug = 'checkbox-default';

    protected bool $isAvailable = false;

    protected bool $isSelected = false;

    public function __construct() {
        $this->displayType = CatalogFilterTypeEnum::checkbox()->value;
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function isSelected(): bool
    {
        return $this->isSelected;
    }

    public function getFilterData(): array
    {
        return [
            'title'         => $this->getTitle(),
            'slug'          => $this->getSlug(),
            'displayType'   => $this->getDisplayType(),
            'isAvailable'   => $this->isAvailable(),
            'isSelected'    => $this->isSelected(),
        ];
    }
}
