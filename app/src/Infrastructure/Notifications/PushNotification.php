<?php

namespace Infrastructure\Notifications;

use Domain\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Infrastructure\Notifications\Channels\AppMetrica\AppMetricaChannel;
use Infrastructure\Services\Yandex\AppMetrica\AppMetricaPushMessage;

class PushNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $title,
        public ?string $text = null,
        public ?string $url = null,
        public ?string $deeplink = null,
    ) {
    }
//
//    public function shouldSend($notifiable, $channel): bool
//    {
//        /** @var User $notifiable */
//        return (bool) $notifiable->mobileTokens()->first();
//    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [
            'database',
            //AppMetricaChannel::class,
        ];
    }

    /**
     * @param $notifiable
     * @return AppMetricaPushMessage
     */
    public function toAppMetrica($notifiable): AppMetricaPushMessage
    {
        $message = (new AppMetricaPushMessage())
            ->title($this->title)
            ->text($this->text);

        if ($this->deeplink) {
            $message->deeplink($this->deeplink);
        } elseif ($this->url) {
            $message->url($this->url);
        }

        $message->setNotifiable($notifiable);

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'title'     => $this->title,
            'text'      => $this->text,
            'url'       => $this->url,
            'deeplink'  => $this->deeplink,
        ];
    }
}
