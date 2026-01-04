<?php

declare(strict_types=1);

namespace Domain\Exchange\Requests;

use Illuminate\Validation\Rule;

class StoreItemRequest extends ItemRequest
{
    public function rules(): array
    {
        return [
            'city_system_id' => [
                'required',
                'string',
                'max:36',
                'exists:cities,system_id',
            ],
            'latitude' => [
                'nullable',
                'numeric',
                'regex:/^-?\d+(\.\d{1,7})?$/',
            ],
            'longitude' => [
                'nullable',
                'numeric',
                'regex:/^-?\d+(\.\d{1,7})?$/',
            ],
            'system_id' => [
                'required',
                'string',
                'max:36',
                'unique:regions,system_id',
            ],
            'legal_entity_system_id' => [
                'nullable',
                'string',
                'max:36',
                'exists:legal_entities,system_id',
            ],
            'active' => [
                'required',
                'boolean',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }
}
