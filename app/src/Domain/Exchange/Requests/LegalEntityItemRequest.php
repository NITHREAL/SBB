<?php

namespace Domain\Exchange\Requests;

class LegalEntityItemRequest extends ItemRequest
{
    public function rules(): array
    {
        return [
            'system_id'       => 'required|string|max:255',
            'title'       => 'required|string|max:255',
            'short_title' => 'string|max:255|nullable',
            'full_title'  => 'string|max:255|nullable',
            'first_name'  => 'string|max:255|nullable',
            'second_name' => 'string|max:255|nullable',
            'last_name'   => 'string|max:255|nullable',
            'certificate' => 'string|max:255|nullable',
            'certificate_date' => 'date_format:Y-m-d|nullable',
            'inn'   => 'string|max:255|nullable',
            'ogrn'  => 'string|max:255|nullable',
            'okato' => 'string|max:255|nullable',
            'okpo'  => 'string|max:255|nullable',
        ];
    }
}
