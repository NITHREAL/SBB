<?php

namespace Domain\User\Requests\Category;

use Infrastructure\Http\Requests\BaseRequest;
use Infrastructure\Rules\MonthYearPeriod;

class FavoriteCategoryCreateRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'categories'    => 'required|array|min:1|max:3',
            'categories.*'  => 'required|integer|exists:favorite_categories,id',
            'period'        => ['required', 'string', new MonthYearPeriod()],
        ];
    }

    public function messages(): array
    {
        return [
            'categories.min' => 'Минимальное количество выбранных категорий - 1',
            'categories.max' => 'Максимальное количество выбранных категорий - 3',
        ];
    }
}
