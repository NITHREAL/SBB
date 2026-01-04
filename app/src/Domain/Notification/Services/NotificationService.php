<?php

namespace Domain\Notification\Services;

use Domain\User\Models\User;

class NotificationService
{
    public function markReadOne(User $user, string $notificationId): void
    {
        $notification = $user->notifications()->where('id', $notificationId)->firstOrFail();

        $notification->markAsRead();
    }

    public function markReadAll(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }

    public function removeAll(User $user): void
    {
        $user->notifications()->delete();
    }
}
