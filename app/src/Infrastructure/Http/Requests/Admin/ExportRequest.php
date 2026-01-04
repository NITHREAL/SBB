<?php

namespace Infrastructure\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExportRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type'   => 'required',
            'filter' => 'array',
        ];
    }
}
