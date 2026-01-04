<?php

namespace Domain\User\DTO\Settings;

use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class UserSettingsDTO extends BaseDTO
{
    public function __construct(
        private readonly bool $allowNotify,
        private readonly bool $allowNotifyPush,
        private readonly bool $allowNotifyEmail,
        private readonly bool $allowNotifySms,
        private readonly bool $allowPhoneCalls,
        private readonly User $user,
    ) {
    }

    public static function make(array $data, User $user): self
    {
        return new self(
            Arr::get($data, 'allowNotify', false),
            Arr::get($data, 'allowNotifyPush', false),
            Arr::get($data, 'allowNotifyEmail', false),
            Arr::get($data, 'allowNotifySms', false),
            Arr::get($data, 'allowPhoneCalls', false),
            $user,
        );
    }

    public function getAllowNotify(): bool
    {
        return $this->allowNotify;
    }

    public function getAllowNotifyPush(): bool
    {
        return $this->allowNotifyPush;
    }

    public function getAllowNotifyEmail(): bool
    {
        return $this->allowNotifyEmail;
    }

    public function getAllowNotifySms(): bool
    {
        return $this->allowNotifySms;
    }

    public function getAllowPhoneCalls(): bool
    {
        return $this->allowPhoneCalls;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
