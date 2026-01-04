<?php

namespace Infrastructure\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CustomInternalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $additionalData = [];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        protected string $title,
        protected string $body,
        public ?string $url,
        public ?string $deeplink,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data = array_merge([
            'title' => $this->title,
            'body'  => $this->body,
            'url'       => $this->url,
            'deeplink'  => $this->deeplink,
        ], $this->additionalData);

        return $data;
    }

    public function withAdditionalData(array $data): self
    {
        $this->additionalData = $data;
        return $this;
    }
}
