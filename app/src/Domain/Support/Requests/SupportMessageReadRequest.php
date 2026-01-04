<?php

namespace Domain\Support\Requests;

use Infrastructure\Http\Requests\BaseRequest;

class SupportMessageReadRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'messageIds'    => 'required|array',
            'messageIds.*'  => 'int',
        ];
    }
}
