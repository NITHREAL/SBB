<?php

namespace Domain\Lottery\Requests\Admin;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Infrastructure\Http\Requests\BaseRequest;

class LotteryChangeRequest extends BaseRequest
{
    public function rules(): array
    {
        $id = (int) Arr::get(request()->route()->parameters(), 'id', 0);

        return [
            'title'                 => [
                'required',
                'string',
                'max:255',
                Rule::unique('promo_actions','title')->ignore($id),
            ],
            'description'           => ['nullable', 'string'],
            'active_from'           => ['nullable', 'date', 'date_format:d-m-Y'],
            'active_to'             => ['nullable', 'date', 'date_format:d-m-Y', 'after:date_from'],
            'sort'                  => ['nullable', 'integer', 'min:0'],
            'active'                => ['boolean'],
            'images'                => ['array', 'max:1'],
            'images.*'              => ['nullable', 'integer', 'exists:attachments,id'],
            'imagesMini'            => ['array', 'max:1'],
            'imagesMini.*'          => ['nullable', 'integer', 'exists:attachments,id'],
            'products'              => ['array'],
            'products.*.id'         => ['required_with:products','integer', 'exists:products,id'],
            'products.*.pivot.sort' => ['integer', 'nullable', 'min:0', 'max:10000'],
        ];
    }
}
