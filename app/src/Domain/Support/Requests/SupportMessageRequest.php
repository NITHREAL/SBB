<?php

namespace Domain\Support\Requests;

use Infrastructure\Http\Requests\BaseRequest;

class SupportMessageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'text'          => 'required|string|max:300',
            'stuffOnly'     => 'boolean',
        ];
    }
}
