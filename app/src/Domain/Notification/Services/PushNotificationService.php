<?php

namespace Domain\Notification\Services;

use Domain\CouponCategory\Models\CouponCategory;
use Domain\User\Models\User;
use Infrastructure\Notifications\CustomInternalNotification;
use Infrastructure\Notifications\PushNotification;

class PushNotificationService
{
    private string $startPushAvailableTime;
    private string $endPushAvailableTime;

    public function __construct() {
        $this->startPushAvailableTime = config('api.notifications.push.available_time.start');
        $this->endPushAvailableTime = config('api.notifications.push.available_time.end');
    }

    public function sendOrderBonusNotification(object $order): void {
        $bonus = $order->amount_bonus;
        $sign = $bonus > 0 ? '+' : '-';
        $saleTime = $order->completed_at ?: $order->created_at;

        $title = __(
            'messages.notifications.order_bonus_scores.title',
            ['sign' => $sign, 'scores' => $bonus],
        );
        $body = __(
            'messages.notifications.order_bonus_scores.message',
            ['date' => $saleTime->format('d.m.Y')],
        );

        $this->sendNotification($order->user, $title, $body);
    }

    public function sendCategoryCouponNotification(User $user, CouponCategory $category): void
    {
        $title = __(
            'messages.notifications.category_coupon.title',
            ['scores' => $category->price / 100],
        );
        $body = __(
            'messages.notifications.category_coupon.message',
            ['name' => $category->name_for_client],
        );

        $this->sendNotification($user, $title, $body);
    }

    private function sendNotification(
        User $user,
        string $title,
        string $text,
    ): void {
        if ($this->isPushAvailable()) {
            $notification = new PushNotification($title, $text);
        } else {
            $notification = new CustomInternalNotification($title, $text);
        }

        $user->notify($notification);
    }

    private function isPushAvailable(): bool
    {
        $currentTime = date('H:i');

        return $currentTime > $this->startPushAvailableTime && $currentTime < $this->endPushAvailableTime;
    }
}
