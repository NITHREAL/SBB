<?php

namespace Domain\Exchange\Factories;

use Domain\Exchange\Enums\RabbitMQQueueEnum;
use Domain\Order\Handlers\OrderHandler;
use Infrastructure\Services\Messaging\MessageHandler;
use InvalidArgumentException;

class MessageHandlerFactory
{
    public function getHandler(string $queueName): MessageHandler
    {
        return match ($queueName) {
            RabbitMQQueueEnum::order()->value => new OrderHandler(),
            default => throw new InvalidArgumentException("Обработчик для очереди $queueName не найден"),
        };
    }
}