<?php

declare(strict_types=1);

namespace Domain\Exchange\Requests;

use Illuminate\Validation\Rule;

class CityItemRequest extends ItemRequest
{
    public function rules(): array
    {
        return [
            'system_id' => [
                'required',
                'string',
            ],
            'region_system_id' => [
                'required',
                'string',
                'exists:regions,system_id',
            ],
            'fias_id' => [
                'required',
                'string',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'sort' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
}
