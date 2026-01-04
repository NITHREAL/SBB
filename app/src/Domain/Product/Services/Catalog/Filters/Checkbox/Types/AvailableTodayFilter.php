<?php

namespace Domain\Product\Services\Catalog\Filters\Checkbox\Types;

use Domain\Product\Services\Catalog\Filters\Checkbox\BaseCheckboxFilter;
use Illuminate\Support\Collection;

class AvailableTodayFilter extends BaseCheckboxFilter
{
    protected string $title = 'Можно заказать сегодня';

    protected string $slug = 'available_today';

    public function __construct(
        bool $isSelected,
        Collection $products,
    ) {
        parent::__construct();

        $this->isSelected = $isSelected;
        $this->isAvailable = true; // TODO доделать когда появится логика с датами
    }
}
