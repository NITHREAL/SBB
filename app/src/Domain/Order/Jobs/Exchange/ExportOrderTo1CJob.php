<?php

namespace Domain\Order\Jobs\Exchange;

use Domain\Order\Models\Order;
use Domain\Order\Services\Exchange\OrderExchangeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportOrderTo1CJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(protected Order $order)
    {
    }

    public function handle(OrderExchangeService $orderExchangeService): void
    {
        $orderExchangeService->exportOrderTo1C($this->order);
    }
}
