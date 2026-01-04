<?php

namespace Domain\Order\Requests\Review;

use Infrastructure\Http\Requests\BaseRequest;

class OrderReviewRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'text'      => 'nullable|string|max:500',
            'rating'    => 'required|integer|min:1|max:5',
        ];
    }
}
