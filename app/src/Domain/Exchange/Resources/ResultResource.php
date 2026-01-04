<?php

namespace Domain\Exchange\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResultResource extends JsonResource
{
    public function toArray($request): array
    {
        $result = [];

        foreach ($this->resource as $resource) {
            $data = [
                'system_id' => (string)$resource['system_id']
            ];

            if (isset($resource['id'])) {
                $data['id'] = $resource['id'];
            }

            if (isset($resource['reasons'])) {
                $data['reasons'] = $resource['reasons'];
            }

            $result[] = $data;
        }

        return $result;
    }
}
