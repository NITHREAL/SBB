<?php

namespace Domain\Product\Requests\Favorite;

use Infrastructure\Http\Requests\PaginatedRequest;

class FavoriteRequest extends PaginatedRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        return array_merge($rules, [
            'storeOneCId'   => 'string',
        ]);
    }
}
