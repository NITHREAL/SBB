<?php

namespace Domain\Product\Jobs\Courier;

use Domain\Product\DTO\Exchange\Courier\ProductDTO;
use Domain\Product\Models\Product;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Messaging\RabbitMQ\RabbitMQPublisher;
use JsonException;

class PublishProductToAmqp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    protected Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @throws JsonException
     */
    public function handle(): void
    {
        $product = ProductDTO::fromModel($this->product)->toArray();

        RabbitMQPublisher::publish(
            'courier',
            'product',
            'courier-product-queue',
            $product
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
