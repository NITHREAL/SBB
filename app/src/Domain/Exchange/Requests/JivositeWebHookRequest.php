<?php

namespace Domain\Exchange\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JivositeWebHookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'sender.id' => 'string',
            'payload.sender.name' => 'string',
            'payload.sender.email' => 'string',

            'payload.recipient.id' => 'string',

            'payload.message.type' => 'string',
            'payload.message.id' => 'string',
            'payload.message.date' => 'string',
            'payload.message.text' => 'string',

        ];
    }
}
