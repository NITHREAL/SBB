<?php

declare(strict_types=1);

namespace Domain\Exchange\Requests;

class LeftoverItemRequest extends ItemRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'system_id' => 'required|exists:stores,system_id',
            'products.*.system_id' => 'required|string',
            'products.*.active' => 'boolean|nullable',
            'products.*.price' => 'numeric|min:0|nullable',
            'products.*.price_discount' => 'numeric|min:0|nullable',
            'products.*.discount_expires_in' => 'date|nullable',
            'products.*.count' => 'numeric|min:0',
            'products.*.delivery_schedule' => 'array|nullable',
            'products.*.delivery_schedule.*' => 'string',
        ];
    }
}
