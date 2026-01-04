<?php

namespace Domain\User\Jobs\LoyaltyCards;

use Domain\User\Models\User;
use Domain\User\Services\Loyalty\Cards\LoyaltyContactCardsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Loyalty\Exceptions\LoyaltyException;

class BindVirtualLoyaltyCardToUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly User $user,
    ) {
    }

    public function handle(LoyaltyContactCardsService $loyaltyContactCardsService): void
    {
        try {
            $loyaltyContactCardsService->addVirtualLoyaltyCardToContact($this->user);
        } catch (LoyaltyException $exception) {
            Log::channel('loyalty')->error($exception->getMessage());
        }
    }
}
