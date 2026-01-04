<?php

namespace Infrastructure\Services\Messaging\RabbitMQ;

use Amqp;
use Illuminate\Support\Facades\Log;
use JsonException;

class RabbitMQPublisher
{
    /**
     * @throws JsonException
     */
    public static function publish(string $exchange, string $routingKey, string $queue, array $message): void
    {
        try {
            Amqp::publish(
                $routingKey,
                json_encode($message, JSON_THROW_ON_ERROR),
                [
                    'exchange' => $exchange,
                    'exchange_type' => 'direct',
                    'queue' => $queue,
                    'content_type' => 'application/json',
                    'delivery_mode' => 2,
                ]
            );
        } catch (JsonException $e) {
            $message = sprintf('Ошибка во время отправки сообщения в рэбит. Ошибка - [%s]', $e->getMessage());

            Log::channel('rabbitmq')->error($message);
        }
    }
}
