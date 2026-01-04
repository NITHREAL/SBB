<?php

namespace Domain\Exchange\Requests;

class OrderExportRequest extends ItemRequest
{
    public function rules(): array
    {
        return [
            'system_ids'    => 'array',
            'system_ids.*'  => 'string'
        ];
    }
}
