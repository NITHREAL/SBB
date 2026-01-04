<?php

namespace Domain\Order\Requests\Admin\Delivery;

use Illuminate\Foundation\Http\FormRequest;

class PolygonTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'polygon_type.title' => 'required|string',
            'polygon_type.description' => 'string|nullable',
            'polygon_type.tooltip' => 'string|nullable',
        ];
    }
}
