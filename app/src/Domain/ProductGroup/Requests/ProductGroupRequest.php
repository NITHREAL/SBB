<?php

declare(strict_types=1);

namespace Domain\ProductGroup\Requests;

use Infrastructure\Http\Requests\PaginatedRequest;

class ProductGroupRequest extends PaginatedRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();

        return array_merge(
            $rules,
            [
                'mobile' => 'nullable',
                'products' => 'sometimes|required_with:storeSlug',
                'storeOneCId' => 'sometimes|string',
            ]
        );
    }
}
