<?php

namespace Domain\Support\Observers;

use Domain\Support\Events\SupportMessageCreated;
use Domain\Support\Helpers\SupportMessageHelper;
use Domain\Support\Models\SupportMessage;
use Infrastructure\Services\Jivosite\JivositeNotification;

class SupportMessageObserver
{
    public function created(SupportMessage $message): void
    {
        if (SupportMessageHelper::isAdminMessage($message)) {
            SupportMessageCreated::dispatch($message);
        } else {
            $message->notify(new JivositeNotification());
        }
    }
}
