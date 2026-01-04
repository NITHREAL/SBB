<?php

namespace Domain\Product\Services\PopularProduct;

use Domain\Product\Models\PopularProduct;
use Domain\Product\Services\BaseSelectionService;

class PopularProductSelectionService extends BaseSelectionService
{
    protected function getSelectionClassName(): string
    {
        return PopularProduct::class;
    }
}
