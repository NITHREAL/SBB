<?php

namespace Domain\Product\Services\ForgottenProduct;

use Domain\Product\Models\ForgottenProduct;
use Domain\Product\Services\BaseSelectionService;

class ForgottenProductSelectionService extends BaseSelectionService
{
    protected function getSelectionClassName(): string
    {
        return ForgottenProduct::class;
    }
}
