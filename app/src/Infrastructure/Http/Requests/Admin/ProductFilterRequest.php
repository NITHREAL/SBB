<?php

namespace Infrastructure\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'filter.active' => 'required|boolean',
            'filter.title' => 'required|string',
            'filter.sort' => 'required|integer|min:0',
        ];
    }
}
