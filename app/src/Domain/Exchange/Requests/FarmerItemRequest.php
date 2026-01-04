<?php

namespace Domain\Exchange\Requests;


use Domain\Exchange\Traits\Fileable;

class FarmerItemRequest extends ItemRequest
{
    use Fileable;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge([
            'system_id' => 'required|string',
            'active' => 'required|boolean',
            'name' => 'required|string',
            'description' => 'string',
            'supply_description' => 'string'
        ], $this->fileRules('image'));
    }
}
