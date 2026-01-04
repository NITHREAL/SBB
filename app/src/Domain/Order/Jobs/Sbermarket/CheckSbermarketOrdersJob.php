<?php

namespace Domain\Order\Jobs\Sbermarket;

use Domain\Order\Enums\OrderSourceEnum;
use Domain\Order\Enums\Payment\PaymentTypeEnum;
use Domain\Order\Models\Order;
use Domain\Order\Services\Sbermarket\SbermarketPaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class CheckSbermarketOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $chunkCount = 10;

    private readonly SbermarketPaymentService $sbermarketPaymentService;

    public function __construct(
        private readonly Collection $orders
    ) {
        $this->sbermarketPaymentService = app()->make(SbermarketPaymentService::class);
    }

    public function handle(): void
    {
        Order::query()
            ->whereIn('id', $this->orders->pluck('id'))
            ->whereWaitingPayment()
            ->whereSource(OrderSourceEnum::sbermarket()->value)
            ->wherePayment(PaymentTypeEnum::byOnline()->value)
            ->with(['payments', 'products'])
            ->chunk($this->chunkCount, function (Collection $orders) {
                $orders->map(fn($order) => $this->sbermarketPaymentService->checkSbermarketOrderPaymentNeed($order));
            });
    }
}
