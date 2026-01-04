<?php

namespace Domain\City\Requests\Admin;

use Domain\City\Models\City;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CityRequest extends FormRequest
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
        $city = $this->route('city');

        return [
            'city.title' => [
                'required',
                Rule::unique(City::class, 'title')->ignore($city)
            ],
            'city.is_settlement' => [
                'required',
                'boolean'
            ],
            'city.latitude' => [
                'numeric',
                'nullable'
            ],
            'city.longitude' => [
                'numeric',
                'nullable'
            ],
            'city.timezone' => [
                'required'
            ],
            'city.sort' => [
                'required', 'integer'
            ],
            'city.region' => [
                'required', 'exists:regions,id'
            ],
            'city.included_settlements.*' => 'exists:cities,id',
        ];
    }
}
