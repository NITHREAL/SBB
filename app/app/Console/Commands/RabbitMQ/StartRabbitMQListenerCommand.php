<?php

namespace App\Console\Commands\RabbitMQ;

use Domain\Exchange\Enums\RabbitMQQueueEnum;
use Domain\Exchange\Factories\MessageHandlerFactory;
use Illuminate\Console\Command;
use Infrastructure\Services\Messaging\RabbitMQ\RabbitMQListener;

class StartRabbitMQListenerCommand extends Command
{
    protected $signature = 'rabbitmq:listen {--queue=all}';

    protected $description = 'Запуск прослушивания RabbitMQ очереди';

    private RabbitMQListener $listener;
    private MessageHandlerFactory $handlerFactory;

    public function __construct(
        RabbitMQListener $listener,
        MessageHandlerFactory $handlerFactory
    ) {
        parent::__construct();
        $this->listener = $listener;
        $this->handlerFactory = $handlerFactory;
    }

    public function handle(): void
    {
        $queueName = $this->option('queue');

        $queues = $queueName === 'all'
            ? RabbitMQQueueEnum::toValues()
            : [$queueName];

        foreach ($queues as $queueName) {
            $this->info("Запуск слушателя для очереди: $queueName");

            $this->startConsumer($queueName);
        }
    }

    private function startConsumer(string $queueName): void
    {
        $handler = $this->handlerFactory->getHandler($queueName);

        $this->listener->listen($queueName, function ($message) use ($handler) {
            $handler->handle($message);
        });
    }
}
