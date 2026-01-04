<?php

namespace Domain\Store\Jobs\Picker;

use Domain\Store\DTO\Exchange\Picker\StoreDTO;
use Domain\Store\Models\Store;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Messaging\RabbitMQ\RabbitMQPublisher;
use JsonException;

class PublishStoreToAmqp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    protected Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @throws JsonException
     */
    public function handle(): void
    {
        $store = StoreDTO::fromModel($this->store)->toArray();

        RabbitMQPublisher::publish(
            'picker',
            'store',
            'picker-store-queue',
            $store
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
