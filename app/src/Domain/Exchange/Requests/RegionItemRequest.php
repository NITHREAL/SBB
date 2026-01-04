<?php

declare(strict_types=1);

namespace Domain\Exchange\Requests;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class RegionItemRequest extends ItemRequest
{
    public function rules(): array
    {
        return [
            'system_id' => [
                'required',
                'string',
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
