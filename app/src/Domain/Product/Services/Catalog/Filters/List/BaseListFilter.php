<?php

namespace Domain\Product\Services\Catalog\Filters\List;

use Domain\Product\Enums\Catalog\CatalogFilterTypeEnum;
use Domain\Product\Services\Catalog\Filters\BaseFilter;

abstract class BaseListFilter extends BaseFilter implements CatalogListFilter
{
    protected string $title = 'List default';

    protected string $slug = 'list-default';

    protected string $displayType;

    protected array $values = [];

    public function __construct()
    {
        $this->displayType = CatalogFilterTypeEnum::list()->value;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function getFilterData(): array
    {
        return [
            'title'         => $this->getTitle(),
            'slug'          => $this->getSlug(),
            'displayType'   => $this->getDisplayType(),
            'values'        => $this->getValues(),
        ];
    }
}
