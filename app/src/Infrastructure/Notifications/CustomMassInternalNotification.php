<?php

namespace Infrastructure\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CustomMassInternalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        public string $title,
        public string $body,
        public ?string $url,
        public ?string $deeplink,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title'     => $this->title,
            'body'      => $this->body,
            'url'       => $this->url,
            'deeplink'  => $this->deeplink,
        ];
    }
}
