<?php

namespace Infrastructure\Services\SMS\Sender\SmsRu;

use DateTimeInterface;
use Infrastructure\Services\SMS\Sender\SmsMessageInterface;

class SmsRuMessage implements SmsMessageInterface
{
    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public string $from = '';

    /**
     * The message content.
     *
     * @var string
     */
    public string $content = '';

    /**
     * Time of sending a message.
     *
     * @var DateTimeInterface
     */
    public DateTimeInterface $sendAt;

    /**
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;
        $this->sendAt = now();
    }

    /**
     * Create a new message instance.
     *
     * @param string $content
     *
     * @return static
     */
    public static function create(string $content = ''): static
    {
        return new static($content);
    }

    /**
     * Set the message content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function content(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the phone number or sender name the message should be sent from.
     *
     * @param string $from
     *
     * @return $this
     */
    public function from(string $from): static
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the time the message should be sent.
     *
     * @param  DateTimeInterface|null  $sendAt
     *
     * @return $this
     */
    public function sendAt(DateTimeInterface $sendAt = null): static
    {
        $this->sendAt = $sendAt;

        return $this;
    }
}
