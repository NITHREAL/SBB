<?php

namespace Domain\Product\Services\WeekProduct;

use Domain\Product\Models\WeekProduct;
use Domain\Product\Services\BaseSelectionService;

class WeekProductSelectionService extends BaseSelectionService
{
    protected function getSelectionClassName(): string
    {
        return WeekProduct::class;
    }
}
