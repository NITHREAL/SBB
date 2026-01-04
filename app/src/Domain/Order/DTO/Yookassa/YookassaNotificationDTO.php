<?php

namespace Domain\Order\DTO\Yookassa;

use Illuminate\Support\Arr;
use Infrastructure\DTO\BaseDTO;

class YookassaNotificationDTO extends BaseDTO
{
    public function __construct(
        private readonly string $event,
        private readonly YookassaNotificationObjectDTO $payment,
    ) {
    }

    public static function make(array $data): self
    {
        $payment = self::getNotificationObject(Arr::get($data, 'object'));

        return new self(
            Arr::get($data, 'event'),
            $payment,
        );
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function getPayment(): YookassaNotificationObjectDTO
    {
        return $this->payment;
    }

    private static function getNotificationObject(array $objectData): YookassaNotificationObjectDTO
    {
        return YookassaNotificationObjectDTO::make($objectData);
    }
}
