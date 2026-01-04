<?php

namespace Domain\Exchange\Requests;


use Domain\Exchange\Traits\Fileable;

class UnitItemRequest extends ItemRequest
{
    use Fileable;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'system_id' => 'required|string',
            'title' => 'required|string'
        ];
    }
}
