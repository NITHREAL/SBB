<?php

namespace Domain\User\Models\Accessors\User;

use Domain\User\Models\User;

final class FullName
{
    public function __construct(
        private readonly User $user,
    ) {
    }

    public function __invoke(): ?string
    {
        $middleName = $this->user->middle_name;

        $fullName = !empty($middleName)
            ? sprintf(
                '%s %s %s',
                $this->user->last_name,
                $this->user->first_name,
                $middleName,
            )
            : sprintf(
                '%s %s',
                $this->user->last_name,
                $this->user->first_name,
            );

        return trim($fullName);
    }
}
