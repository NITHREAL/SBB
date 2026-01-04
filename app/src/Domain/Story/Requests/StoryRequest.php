<?php

declare(strict_types=1);

namespace Domain\Story\Requests;

use Infrastructure\Http\Requests\PaginatedRequest;

class StoryRequest extends PaginatedRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();

        return array_merge($rules, [
            'mobile' => 'nullable|sometimes|present',
        ]);
    }
}
