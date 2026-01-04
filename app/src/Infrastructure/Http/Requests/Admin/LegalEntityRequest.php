<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LegalEntityRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $entity = $this->legal_entity;

        if (!$entity['sber_password']) {
            unset($entity['sber_password']);
        }

        if (!$entity['hash_key']) {
            unset($entity['hash_key']);
        }

        $this->merge([
            'legal_entity' => $entity
        ]);
    }

    public function rules()
    {
        $entity = $this->route('entity');

        return [
            'legal_entity.title' => 'required',
            'legal_entity.short_title' => 'string|nullable',
            'legal_entity.full_title' => 'string|nullable',
            'legal_entity.first_name' => 'string|nullable',
            'legal_entity.second_name' => 'string|nullable',
            'legal_entity.last_name' => 'string|nullable',
            'legal_entity.certificate' => 'string|nullable',
            'legal_entity.certificate_date' => 'date_format:Y-m-d|nullable',
            'legal_entity.inn' => 'string|nullable',
            'legal_entity.ogrn' => 'string|nullable',
            'legal_entity.okato' => 'string|nullable',
            'legal_entity.okpo' => 'string|nullable',
            'legal_entity.sber_login' => [
                'required',
                Rule::unique('legal_entities', 'sber_login')->ignore($entity)
            ],
            'legal_entity.sber_password' => 'string',
            'legal_entity.hash_key' => 'string',
        ];
    }
}
