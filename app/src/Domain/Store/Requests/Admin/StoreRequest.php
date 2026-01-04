<?php

namespace Domain\Store\Requests\Admin;

use App\Rules\PhoneOrEmail;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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

    public function prepareForValidation()
    {
        $this->merge([
            'store' => $this->store + [
                    'latitude' => $this->coords['lat'],
                    'longitude' => $this->coords['lng']
                ]
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'store.set_id' => 'integer|nullable',
            'store.active' => 'required|boolean',
            'store.title' => 'required|string',
            'store.sort' => 'integer|nullable',
            'store.legal_entity_1c_id' => 'exists:legal_entities,1c_id|nullable',
            'store.address' => 'required:string',
            'store.city_id' => 'required|integer',
            'store.latitude' => 'numeric',
            'store.longitude' => 'numeric',
            'store.work_time' => 'string|nullable',
            'store.contacts.*.id' => 'integer|nullable',
            'store.contacts.*.type' => ['required'],
            'store.contacts.*.value' => ['required', 'string', new PhoneOrEmail()],
            'store.scheduleWeekdays.*.id' => 'integer|nullable',
            'store.scheduleWeekdays.*.polygon_type_id' => 'nullable',
            'store.scheduleWeekdays.*.week_day' => ['required'],
            'store.scheduleWeekdays.*.from' => 'date_format:H:i|nullable',
            'store.scheduleWeekdays.*.to' => 'date_format:H:i|nullable',
            'store.scheduleWeekdays.*.not_working' => 'boolean',
            'store.scheduleDates.*.id' => 'integer|nullable',
            'store.scheduleDates.*.polygon_type_id' => 'nullable',
            'store.scheduleDates.*.date' => 'required|date_format:Y-m-d',
            'store.scheduleDates.*.from' => 'date_format:H:i|nullable',
            'store.scheduleDates.*.to' => 'date_format:H:i|nullable',
            'store.scheduleDates.*.not_working' => 'boolean',
            'store.payments' => 'array',
            'store.payments.*.id' => 'integer',
            'store.payments_from_city' => 'boolean',
            'store.is_dark_store' => 'boolean',
            'polygons.*' => 'json'
        ];
    }
}
