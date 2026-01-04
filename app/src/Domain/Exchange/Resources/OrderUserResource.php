<?php

namespace Domain\Exchange\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (int)$this->id,
            'first_name' => (string)$this->first_name,
            'last_name' => (string)$this->last_name,
            'sex' => (string)$this->sex,
            'phone' => (string)$this->phone,
            'email' => (string)$this->email ?? 'undefined@box.ru',
            'birthday' => (string)$this->birthday
        ];
    }
}
