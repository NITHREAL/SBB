<?php

namespace Domain\Notification\DTO;

use Infrastructure\DTO\BaseDTO;

class MassNotificationDTO extends BaseDTO
{
    public function __construct(
        public string $title,
        public string $text,
        public ?string $url,
        public ?string $deeplink,
    ) {
    }
}
