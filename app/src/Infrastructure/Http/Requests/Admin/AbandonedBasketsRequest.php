<?php

namespace Infrastructure\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AbandonedBasketsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'abandoned_over_in_hours' => 'required|int',
            'user_id'=> 'int|nullable',
            'summ_min' => 'int|min:0|nullable',
            'summ_max' => 'int|min:0|nullable',
            'available' => 'boolean|nullable'
        ];
    }
}
