<?php

namespace Domain\User\DTO\Profile;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class ProfileDTO extends BaseDTO
{
    public function __construct(
        private readonly ?string  $firstName,
        private readonly ?string $middleName,
        private readonly ?string $lastName,
        private readonly ?string $birthdate,
        private readonly ?string $email,
        private readonly ?string $sex,
        private readonly User $user,
    ) {
    }

    public static function make(array $data, $user): self
    {
        return new self(
            Arr::get($data, 'firstName'),
            Arr::get($data, 'middleName'),
            Arr::get($data, 'lastName'),
            Arr::get($data, 'birthdate'),
            Arr::get($data, 'email'),
            Arr::get($data, 'sex'),
            $user,
        );
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthdate;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
