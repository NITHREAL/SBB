<?php

namespace Domain\Product\Requests\Catalog;

use Infrastructure\Http\Requests\BaseRequest;

class GetProductRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'relatedProductsLimit'  => 'integer|min:1|max:100',
        ];
    }
}
