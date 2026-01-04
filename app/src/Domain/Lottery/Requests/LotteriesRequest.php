<?php

namespace Domain\Lottery\Requests;

use Infrastructure\Http\Requests\PaginatedRequest;

class LotteriesRequest extends PaginatedRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        return array_merge(
            $rules,
            [
                'products'      => 'boolean',
                'productsLimit' => 'integer|min:1|max:50',
            ],
        );
    }
}
