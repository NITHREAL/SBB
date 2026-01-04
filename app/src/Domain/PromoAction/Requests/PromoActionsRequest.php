<?php

namespace Domain\PromoAction\Requests;

use Infrastructure\Http\Requests\PaginatedRequest;

class PromoActionsRequest extends PaginatedRequest
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
