<?php

namespace Domain\Exchange\Requests;

class ProductLeftoverRequest extends ItemRequest
{
    public function rules(): array
    {
        return [
            'products.*' => 'required|string'
        ];
    }
}
