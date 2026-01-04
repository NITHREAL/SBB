<?php

namespace Infrastructure\Services\Messaging\RabbitMQ;

use Amqp;
use Exception;
use Illuminate\Support\Facades\Log;

class RabbitMQListener
{
    public function listen(string $queueName, callable $callback): void
    {
        try {
            Amqp::consume(
                $queueName,
                static function ($message, $resolver) use ($callback) {
                    try {
                        $data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR);

                        $callback($data);

                        $resolver->acknowledge($message);
                    } catch (Exception $e) {
                        Log::error('Ошибка обработки сообщения RabbitMQ: ' . $e->getMessage());

                        $resolver->reject($message, false);
                    }
                },
                [
                    'persistent' => true,
                ]
            );
        } catch (Exception $e) {
            $message = sprintf('Ошибка во время получения сообщения от рэбит. Ошибка - [%s]', $e->getMessage());

            Log::channel('rabbitmq')->error($message);
        }
    }
}
