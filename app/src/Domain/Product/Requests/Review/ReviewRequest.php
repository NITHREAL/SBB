<?php

namespace Domain\Product\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'slug' => 'required|exists:products,slug',
            'rating' => 'required|integer|min:1|max:5',
            'text' => 'nullable|string|max:300',
        ];
    }
}
