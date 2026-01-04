<?php

namespace Infrastructure\Notifications\Sms;

use Illuminate\Notifications\Notification;
use Infrastructure\Services\SMS\Sender\SmsMessageInterface;

class VerifySmsCode extends Notification
{
    protected SmsMessageInterface $message;

    public function __construct(
        protected string $code,
    ) {
        $this->message = app()->make(SmsMessageInterface::class);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['sms'];
    }

    public function getMessage(mixed $notifiable): SmsMessageInterface
    {
        return $this->message::create(
            sprintf('Код для подтверждения: %s', $this->code),
        );
    }
}
