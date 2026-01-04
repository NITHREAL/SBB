<?php

namespace Domain\Product\Requests\Search;

use Infrastructure\Http\Requests\CatalogRequest;

class SearchRequest extends CatalogRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        return array_merge($rules, [
            'search'        => 'required|string|max:100',
        ]);
    }
}
