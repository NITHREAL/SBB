<?php

namespace Domain\Support\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportMessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'        => $this->resource->id,
            'text'      => $this->resource->text,
            'author'    => $this->resource->preparedAuthor,
            'viewed'    => $this->resource->viewed,
            'createdAt' => $this->resource->created_at,
        ];
    }
}
