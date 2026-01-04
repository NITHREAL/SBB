<?php

namespace Infrastructure\Http\Requests\Admin;

use Domain\UtmLabel\Enums\UtmLabelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UtmLabelRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return array_merge([
            'label.type' => ['required', Rule::in(UtmLabelEnum::toValues())],
            'label.value' => 'required|string',
            'label.description' => 'string|nullable',
        ]);
    }
}
