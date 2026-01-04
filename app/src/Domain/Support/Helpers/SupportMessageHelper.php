<?php

namespace Domain\Support\Helpers;

use Domain\Support\Enums\SupportMessageAuthorEnum;
use Domain\Support\Models\SupportMessage;

class SupportMessageHelper
{
    public static function isAdminMessage(SupportMessage $supportMessage): bool
    {
        return $supportMessage->author === SupportMessageAuthorEnum::administrator()->value;
    }
}
