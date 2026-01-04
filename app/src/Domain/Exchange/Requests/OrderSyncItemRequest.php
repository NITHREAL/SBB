<?php

namespace Domain\Exchange\Requests;

class OrderSyncItemRequest extends ItemRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'system_id' => 'required|string',
            'id' => 'required|integer'
        ];
    }
}
