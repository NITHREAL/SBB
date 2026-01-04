<?php

namespace Infrastructure\Services\Loyalty\RequestDTO\Manzana\Contact;

use Domain\User\Enums\UserSexEnum;
use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\RequestDTO\BaseDTO;

class UpdateContactDTO extends BaseDTO
{
    public function __construct(
        private readonly string $sessionId,
        private readonly string $loyaltyUserId,
        private readonly ?string $lastName,
        private readonly ?string $firstName,
        private readonly ?string $middleName,
        private readonly ?int $genderCode,
        private readonly ?string $birthDate,
        private readonly ?string $email,
        private readonly bool $allowEmail,
        private readonly bool $allowSms,
        private readonly bool $allowPhone,
        private readonly bool $allowNotification,
    ) {
    }

    public static function make(array $data): self
    {
        $genderCode = self::getPreparedGenderCode(Arr::get($data, 'gender'));

        return new self(
            Arr::get($data, 'sessionId'),
            Arr::get($data, 'loyaltyUserId'),
            Arr::get($data, 'lastName'),
            Arr::get($data, 'firstName'),
            Arr::get($data, 'middleName'),
            $genderCode,
            Arr::get($data, 'birthdate'),
            Arr::get($data, 'email'),
            Arr::get($data, 'allowEmail'),
            Arr::get($data, 'allowSms'),
            Arr::get($data, 'allowPhone'),
            Arr::get($data, 'allowNotification'),

        );
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getLoyaltyUserId(): string
    {
        return $this->loyaltyUserId;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getGenderCode(): ?int
    {
        return $this->genderCode;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function isAllowEmail(): bool
    {
        return $this->allowEmail;
    }

    public function isAllowSms(): bool
    {
        return $this->allowSms;
    }

    public function isAllowPhone(): bool
    {
        return $this->allowPhone;
    }

    public function isAllowNotification(): bool
    {
        return $this->allowNotification;
    }

    private static function getPreparedGenderCode(?string $gender): ?int
    {
        $result = null;

        if ($gender) {
            $result = $gender === UserSexEnum::male()->value ? 1 : 2;
        }

        return $result;
    }
}
