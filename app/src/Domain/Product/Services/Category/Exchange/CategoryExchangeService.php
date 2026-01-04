<?php

declare(strict_types=1);

namespace Domain\Product\Services\Category\Exchange;

use Domain\Product\DTO\Exchange\CategoryExchangeDTO;
use Domain\Product\Models\Category;

class CategoryExchangeService
{
    public function exchange(CategoryExchangeDTO $categoryDTO): Category
    {
        $attributes = ['system_id' => $categoryDTO->getSystemId()];

        return Category::updateOrCreate($attributes, $categoryDTO->toArray());
    }
}

