<?php

namespace Domain\Unit\Jobs\Courier;

use Domain\Unit\DTO\Exchange\Picker\UnitDTO;
use Domain\Unit\Models\Unit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Messaging\RabbitMQ\RabbitMQPublisher;

class PublishUnitToAmqp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    public function __construct(
        private readonly Unit $unit,
    ) {
    }

    public function handle(): void
    {
        $unit = UnitDTO::fromModel($this->unit)->toArray();

        RabbitMQPublisher::publish(
            'courier',
            'unit',
            'courier-unit-queue',
            $unit,
        );
    }
    public function failed($exception): void
    {
        Log::channel('message')
            ->error(
                'Задача отправки в RabbitMQ провалилась.',
                ['error' => $exception->getMessage()]
            );
    }
}
