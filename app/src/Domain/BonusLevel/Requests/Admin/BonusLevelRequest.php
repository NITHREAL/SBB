<?php

namespace Domain\BonusLevel\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BonusLevelRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bonusLevel.number' => 'required|integer',
            'bonusLevel.title' => 'required|string|max:255',
            'bonusLevel.min_bonus_points' => 'required|integer|min:0',
            'bonusLevel.max_bonus_points' => 'nullable|integer|min:0|gt:bonusLevel.min_bonus_points',
        ];
    }

    public function messages(): array
    {
        return [
            'bonusLevel.number.required' => __('validation.Number is required'),
            'bonusLevel.number.integer' => __('validation.Number must be an integer'),
            'bonusLevel.title.required' => __('validation.Title is required'),
            'bonusLevel.title.string' => __('validation.Title must be a string'),
            'bonusLevel.title.max' => __('validation.Title may not be greater than 255 characters'),
            'bonusLevel.min_bonus_points.required' => __('validation.Minimum bonus points are required'),
            'bonusLevel.min_bonus_points.integer' => __('validation.Minimum bonus points must be an integer'),
            'bonusLevel.min_bonus_points.min' => __('validation.Minimum bonus points must be at least 0'),
            'bonusLevel.max_bonus_points.integer' => __('validation.Maximum bonus points must be an integer'),
            'bonusLevel.max_bonus_points.min' => __('validation.Maximum bonus points must be at least 0'),
            'bonusLevel.max_bonus_points.gt' => __('validation.Maximum bonus points must be greater than minimum bonus points'),
        ];
    }
}
