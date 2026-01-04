<?php

namespace Domain\User\Resources\Profile;

use Domain\User\Enums\UserSexEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => (int)$this->id,
            'firstName'     => (string)$this->first_name,
            'middleName'    => (string)$this->middle_name,
            'lastName'      => (string)$this->last_name,
            'phone'         => (string)$this->phone,
            'email'         => $this->email,
            'sex'           => $this->sex,
            'birthdate'     => $this->birthdate,
        ];
    }
}
