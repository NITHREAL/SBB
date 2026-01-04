<?php

namespace Domain\Support\Services;

use Domain\Support\Enums\SupportMessageAuthorEnum;
use Domain\Support\Models\SupportMessage;
use Illuminate\Support\Collection;

class SupportMessageSelectionService
{
    public function getUserSupportMessages(int $userId): Collection
    {
        return SupportMessage::query()
            ->whereUser($userId)
            ->whereOnlyForStuff(false)
            ->orderBy('support_messages.created_at')
            ->get();
    }

    public function getUserSupportMessageUnreadCount(int $userId): int
    {
        return SupportMessage::query()
            ->whereUser($userId)
            ->whereAuthor(SupportMessageAuthorEnum::administrator()->value)
            ->whereUnread()
            ->count();
    }

    public function getAdminSupportMessagesUnreadCount(): int
    {
        return SupportMessage::query()
            ->whereAuthor(SupportMessageAuthorEnum::user()->value)
            ->whereUnread()
            ->count();
    }
}
