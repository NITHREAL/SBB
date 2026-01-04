<?php

namespace Infrastructure\Services\SMS\Sender\SmsRu\Exceptions;

use DomainException;
use Exception;

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when content length is greater than 800 characters.
     *
     * @return static
     */
    public static function contentLengthLimitExceeded(): self
    {
        return new static(
            'Notification was not sent. Content length may not be greater than 800 characters.'
        );
    }

    /**
     * Thrown when we're unable to communicate with sms.ru.
     *
     * @param  DomainException  $exception
     *
     * @return static
     */
    public static function smsRespondedWithAnError(DomainException $exception): self
    {
        return new static(
            "sms.ru responded with an error '{$exception->getCode()}: {$exception->getMessage()}'",
            $exception->getCode(),
            $exception
        );
    }

    /**
     * Thrown when we're unable to communicate with sms.ru.
     *
     * @param  Exception  $exception
     *
     * @return static
     */
    public static function couldNotCommunicateWithSms(Exception $exception): self
    {
        return new static(
            "The communication with sms.ru failed. Reason: {$exception->getMessage()}",
            $exception->getCode(),
            $exception
        );
    }
}
