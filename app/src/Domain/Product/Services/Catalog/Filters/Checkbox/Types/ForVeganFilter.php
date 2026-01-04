<?php

namespace Domain\Product\Services\Catalog\Filters\Checkbox\Types;

use Domain\Product\Services\Catalog\Filters\Checkbox\BaseCheckboxFilter;
use Illuminate\Support\Collection;

class ForVeganFilter extends BaseCheckboxFilter
{
    protected string $title = 'Вегетарианцам';

    protected string $slug = 'for_vegan';

    public function __construct(
        bool $isSelected,
        Collection $products,
    ) {
        parent::__construct();

        $this->isSelected = $isSelected;
        $this->isAvailable = $products->where('vegan', true)->count() > 0;
    }
}
