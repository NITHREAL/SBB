<?php

namespace Domain\Faq\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class FaqRequest extends FormRequest
{
    public function rules(): array
    {
        $questionId = (int) Arr::get(request()->route()->parameters(), 'question', 0);

        return [
            'title' => ['required', 'string'],
            'slug'      => [
                'nullable',
                'string',
                Rule::unique('faq_categories', 'slug')->ignore($questionId)
            ],
            'text'      => ['required', 'string', 'max:1000', 'min:3'],
            'sort'      => ['nullable', 'integer', 'min:0', 'max:1000'],
            'active'    => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Поле "Заголовок" обязательно для заполнения.',
            'title.string' => 'Поле "Заголовок" должно быть строкой.',
            'slug.string' => 'Поле "Слаг" должно быть строкой.',
            'slug.unique' => 'Такой "Слаг" уже существует.',
            'text.required' => 'Поле "Текст" обязательно для заполнения.',
            'text.string' => 'Поле "Текст" должно быть строкой.',
            'text.max' => 'Поле "Текст" не должно превышать 1000 символов.',
            'text.min' => 'Поле "Текст" должно содержать минимум 3 символа.',
            'sort.integer' => 'Поле "Сортировка" должно быть целым числом.',
            'sort.min' => 'Поле "Сортировка" не может быть меньше 0.',
            'sort.max' => 'Поле "Сортировка" не может быть больше 1000.',
            'active.boolean' => 'Поле "Активно" должно быть логическим значением.',
        ];
    }
}
