<?php

namespace Infrastructure\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SlideRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slide.sort' => [
                'integer',
                'min:0'
            ],
            'slide.title' => [
                'required',
                'string'
            ],
            'slide.active' => [
                'required',
                'boolean'
            ],
            'slide.cities.*' => [
                'integer',
            ],
            'slide.user_type' => [
                'string',
                //Rule::in(SlideUserTypesEnum::toValues())
            ],
            'slide.url' => [
                'required',
                'string',
                'url',
            ],
            'slide.button_text' => [
                'string',
            ],
            'slide.mask_color' => [
                'regex:/^\#[\da-f]{6}|\#[\da-f]{3}$/i',
            ],
            'slide.attachment.*' => [
                'required',
                'integer'
            ],
        ];
    }
}
