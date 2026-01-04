<?php

namespace Domain\Order\Jobs\Exchange\Picker;

use Domain\Order\DTO\Exchange\Picker\OrderDTO;
use Domain\Order\Models\Order;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Messaging\RabbitMQ\RabbitMQPublisher;
use JsonException;

class PublishOrderToAmqp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable,  SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    protected Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @throws JsonException
     */
    public function handle(): void
    {
        $order = OrderDTO::fromModel($this->order)->toArray();

        RabbitMQPublisher::publish(
            'picker',
            'order',
            'picker-order-queue',
            $order
        );
    }

    public function failed(Exception $exception): void
    {
        Log::channel('message')
            ->error(
                'Задача отправки в RabbitMQ провалилась.',
                ['error' => $exception->getMessage()]
            );
    }

}
