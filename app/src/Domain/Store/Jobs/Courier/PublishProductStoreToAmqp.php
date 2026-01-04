<?php

namespace Domain\Store\Jobs\Courier;

use Domain\Store\DTO\Exchange\ProductStoreDTO;
use Domain\Store\Models\ProductStore;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Messaging\RabbitMQ\RabbitMQPublisher;
use JsonException;

class PublishProductStoreToAmqp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries = 3;

    public int $backoff = 10;

    protected ProductStore $productStore;

    public function __construct(ProductStore $productStore)
    {
        $this->productStore = $productStore;
    }

    /**
     * @throws JsonException
     */
    public function handle(): void
    {
        $productStore = ProductStoreDTO::fromModel($this->productStore)->toArray();

        RabbitMQPublisher::publish(
            'courier',
            'productStore',
            'courier-product-store-queue',
            $productStore
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
