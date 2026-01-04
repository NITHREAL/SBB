<?php

namespace Domain\City\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RegionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'region.title' => 'required|string',
            'region.sort' => 'required|integer|min:0'
        ];
    }
}
