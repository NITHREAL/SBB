<?php

namespace Domain\Notifycation\Email;

use Domain\Notification\DTO\MassNotificationDTO;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;

class MassMailNotification extends Notification
{
    use Queueable;

    private $massNotificationDTO;

    public function __construct(MassNotificationDTO $massNotificationDTO)
    {
        $this->massNotificationDTO = $massNotificationDTO;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->massNotificationDTO->title)
            ->line($this->massNotificationDTO->text)
            ->action('Посмотреть', $this->massNotificationDTO->url ?? '#')
            ->line('Спасибо за использование нашего приложения!');
    }
}
