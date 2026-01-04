<?php

namespace Domain\Order\Jobs\Sbermarket;

use Domain\Order\Models\Order;
use Domain\Order\Services\Sbermarket\SbermarketStatusService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderSbermarketStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120;

    public int $tries = 5;

    public function __construct(
        private readonly Order $order,
        private readonly string $status
    ) {
    }

    /**
     * @throws HttpClientException
     */
    public function handle(SbermarketStatusService $sbermarketStatusService): void
    {
        $sbermarketStatusService->changeStatus($this->order, $this->status);
    }

    /**
     * экспоненциальная отсрочка.
     *
     * задержка повторной попытки будет составлять 1 секунду для первой попытки,
     * 5 секунд для второй попытки и 10 секунд для третьей попытки
     * [1, 5, 10]
     *
     * @return int[]
     */
    public function backoff(): array
    {
        return [10, 30, 60, 120, 240];
    }
}
