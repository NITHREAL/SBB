<?php

namespace Domain\Exchange\Requests;


use Domain\Exchange\Traits\Fileable;

class GroupItemRequest extends ItemRequest
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
            'title' => 'required|string',
        ], $this->fileRules('image'));
    }
}
