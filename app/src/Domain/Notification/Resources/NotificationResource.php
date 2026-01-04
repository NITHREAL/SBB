<?php

namespace Domain\Notification\Resources;

use Domain\Notification\Enum\NotificationTypeEnum;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class NotificationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => (string)$this->resource->id,
            'body'          => (string)Arr::get($this->resource->data, 'body', ''),
            'title'         => (string)Arr::get($this->resource->data, 'title', ''),
            'deeplink'      => (string)Arr::get($this->resource->data, 'deeplink', ''),
            'url'           => (string)Arr::get($this->resource->data, 'url', ''),
            'type'          => NotificationTypeEnum::tryFrom($this->resource->type)?->label,
            'createdAt'    => $this->resource->created_at->format("d-m-y H:i:s"),
            'isRead'       => !is_null($this->resource->read_at),
        ];
    }
}
