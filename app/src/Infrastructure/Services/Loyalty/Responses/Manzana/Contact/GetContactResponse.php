<?php

namespace Infrastructure\Services\Loyalty\Responses\Manzana\Contact;

use Domain\User\Enums\UserSexEnum;
use Illuminate\Support\Arr;
use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

readonly class GetContactResponse implements ManzanaResponseInterface
{
    public function __construct(
        private string $id,
        private ?string $lastName,
        private ?string $firstName,
        private ?string $middleName,
        private ?string $gender,
        private ?string $birthdate,
        private ?string $email,
        private bool   $allowEmail,
        private bool   $allowPhone,
        private bool   $allowSms,
        private bool   $allowNotifications,
        private float  $balance,
        private ?string $levelId,
        private float  $levelProgress,
    ) {
    }

    public static function make(array $data): self
    {
        $gender = self::getPreparedGender(Arr::get($data, 'GenderCode'));
        $birthdate = self::getPreparedBirthdate(Arr::get($data, 'BirthDate'));

        return new self(
            Arr::get($data, 'Id'),
            Arr::get($data, 'LastName'),
            Arr::get($data, 'FirstName'),
            Arr::get($data, 'MiddleName'),
            $gender,
            $birthdate,
            Arr::get($data, 'EmailAddress'),
            Arr::get($data, 'AllowEmail') ?? false,
            Arr::get($data, 'AllowPhone') ?? false,
            Arr::get($data, 'AllowSms') ?? false,
            Arr::get($data, 'AllowNotification') ?? false,
            Arr::get($data, 'ActiveBalance'),
            Arr::get($data, 'LevelId'),
            Arr::get($data, 'Summ'),
        );
    }

    public function getId(): string
    {
        return $this->id;
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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getAllowEmail(): bool
    {
        return $this->allowEmail;
    }

    public function getAllowPhone(): bool
    {
        return $this->allowPhone;
    }

    public function getAllowSms(): bool
    {
        return $this->allowSms;
    }

    public function getAllowNotifications(): bool
    {
        return $this->allowNotifications;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getLevelId(): ?string
    {
        return $this->levelId;
    }

    public function getLevelProgress(): float
    {
        return $this->levelProgress;
    }

    public function getProfileData(): array
    {
        return [
            'firstName'     => $this->getFirstName(),
            'middleName'    => $this->getMiddleName(),
            'lastName'      => $this->getLastName(),
            'birthdate'     => $this->getBirthdate(),
            'email'         => $this->getEmail(),
            'sex'           => $this->getGender(),
        ];
    }

    public function getSettingsData(): array
    {
        return [
            'allowNotify'       => $this->getAllowNotifications(),
            'allowPhoneCalls'   => $this->getAllowPhone(),
            'allowNotifySms'    => $this->getAllowSms(),
            'allowNotifyEmail'  => $this->getAllowEmail(),
        ];
    }

    private static function getPreparedBirthdate(?string $birthdate): ?string
    {
        $result = null;

        if ($birthdate) {
            $birthdateData = date_parse($birthdate);

            $month = Arr::get($birthdateData, 'month');
            $day = Arr::get($birthdateData, 'day');

            $preparedMonth = strlen($month) < 2 ? sprintf('0%s', $month) : $month;
            $preparedDay = strlen($day) < 2 ? sprintf('0%s', $day) : $day;

            $result = sprintf(
                '%s-%s-%s',
                Arr::get($birthdateData, 'year'),
                $preparedMonth,
                $preparedDay,
            );
        }

        return $result;
    }

    private static function getPreparedGender(?int $genderCode): ?string
    {
        $result = null;

        if ($genderCode) {
            $result = $genderCode === 1 ? UserSexEnum::male()->value : UserSexEnum::female()->value;
        }

        return $result;
    }
}
