<?php

namespace Domain\Support\Jobs;

use Domain\Support\Services\SupportMessageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReadSupportMessagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const DELAY_SECONDS = 5;

    public function __construct(
        private readonly array $messageIds,
    ) {
        $this->delay(self::DELAY_SECONDS);
    }

    public function handle(SupportMessageService $supportMessageService): void
    {
        $supportMessageService->readMessages($this->messageIds);
    }
}
