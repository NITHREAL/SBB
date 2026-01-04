<?php

namespace Domain\Exchange\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderContactResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'phone' => (string)$this->phone,
            'name' => (string)$this->name,
            'email' => (string)$this->email,
            'address' => $this->address,
            'apartment' => $this->apartment,
            'floor' => $this->floor,
            'entrance' => $this->entrance,
            'has_elevator' => (bool)$this->has_elevator,
            'intercom' => (string)$this->intercom,
        ];
    }
}
