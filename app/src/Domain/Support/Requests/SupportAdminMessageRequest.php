<?php

namespace Domain\Support\Requests;

use Infrastructure\Http\Requests\BaseRequest;

class SupportAdminMessageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'text'          => 'required|string|max:300',
            'stuffOnly'     => 'boolean',
            'userId'        => 'required|integer|exists:users,id',
        ];
    }
}
