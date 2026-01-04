<?php

namespace Domain\Farmer\Resources\Review;

use Domain\Product\Models\Review;
use Domain\User\Helpers\UserHelper;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Review
 */
class FarmerReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'rating'    => $this->rating,
            'text'      => $this->text,
            'date'      => $this->created_at,
            'productTitle' => $this->productTitle,
            'user'      => [
                'id'          => $this->user_id,
                'firstName'   => $this->first_name ?? UserHelper::getDefaultUserName(),
                'lastName'    => $this->last_name,
            ],
        ];
    }
}
