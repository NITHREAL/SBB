<?php

namespace Domain\Exchange\Requests;


use Domain\Exchange\Traits\Fileable;

class CategoryItemRequest extends ItemRequest
{
    use Fileable;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge([
            'active' => 'required|boolean',
            'system_id' => 'required|string',
            'parent_system_id' => 'exists:categories,system_id|nullable',
            'title' => 'required|string',
            'slug' => 'required|string'
        ]);
    }
}
