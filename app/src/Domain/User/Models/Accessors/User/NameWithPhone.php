<?php

namespace Domain\User\Models\Accessors\User;

use Domain\User\Models\User;

final class NameWithPhone
{
    public function __construct(
        private readonly User $user,
    ) {
    }

    public function __invoke(): ?string
    {
        $firstName = $this->user->first_name;
        $phone = $this->user->phone;

        $nameWithPhone = !empty($phone)
            ? sprintf('%s %s', $firstName, $phone)
            : $firstName;

        return trim($nameWithPhone);
    }
}

