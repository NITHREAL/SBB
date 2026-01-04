<?php

namespace Infrastructure\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Orchid\Platform\Notifications\DashboardChannel;
use Orchid\Platform\Notifications\DashboardMessage;

class ExportJobCompletedNotification extends Notification
{
    use Queueable;

    public string $title;
    public string $message = '';
    public string $action = '';
    public ?string $downloadUrl;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $title = 'Export complete', string $message = '', string $downloadUrl = null)
    {
        $this->downloadUrl = $downloadUrl;
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DashboardChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toDashboard($notifiable)
    {
        $data = [
            'downloadUrl' => $this->downloadUrl,
            'message' => $this->message,
            'title' => $this->title,
            'action' => $this->action,
        ];
        $data = array_filter($data);
        return (new DashboardMessage($data));
    }
}
