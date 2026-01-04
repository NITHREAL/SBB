<?php

namespace Domain\User\DTO;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Infrastructure\DTO\BaseDTO;
use Infrastructure\Helpers\PhoneFormatterHelper;

class UserCreateDTO extends BaseDTO
{
    public function __construct(
        private readonly ?string $firstName,
        private readonly ?string $lastName,
        private readonly ?string $sex,
        private readonly ?string $email,
        private readonly ?string $birthdate,
        private readonly string $phone,
        private readonly array $permissions,
        private readonly ?string $password,
        private readonly string $registrationType,
    ) {
    }

    public static function make(array $data, bool $isAdmin = false, bool $userExists = false): self
    {
        $permissions = self::getPreparedPermissions(Arr::get($data, 'permissions') ?? []);
        $password = self::getPreparedPassword((string) Arr::get($data, 'password'), $isAdmin, $userExists);
        $phone = self::getPreparedPhone(Arr::get($data, 'phone'));

        return new self(
            Arr::get($data, 'first_name'),
            Arr::get($data, 'last_name'),
            Arr::get($data, 'sex'),
            Arr::get($data, 'email'),
            Arr::get($data, 'birthdate'),
            $phone,
            $permissions,
            $password,
            Arr::get($data, 'registration_type'),
        );
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRegistrationType(): string
    {
        return $this->registrationType;
    }

    private static function getPreparedPermissions(array $permissions): array
    {
        return collect($permissions)
            ->map(function ($value, $key) {
                return [base64_decode($key) => $value];
            })
            ->collapse()
            ->toArray();
    }

    private static function getPreparedPassword(string $password, bool $isAdmin, bool $userExists): ?string
    {
        $result = $password;

        if ($isAdmin) {
            $result = $userExists && empty($password)
                ? null
                : Hash::make($password);
        }

        return $result;
    }

    private static function getPreparedPhone(string $phone): string
    {
        return PhoneFormatterHelper::unformat($phone);
    }
}
