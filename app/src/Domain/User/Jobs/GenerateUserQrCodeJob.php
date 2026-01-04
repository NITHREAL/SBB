<?php

namespace Domain\User\Jobs;

use Domain\User\Models\User;
use Domain\User\Services\UserQrCodeService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateUserQrCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly User $user,
    ) {
    }

    public function handle(UserQrCodeService $userQrCodeService): void
    {
        try {
            $userQrCodeService->generateQrCode($this->user->getCartNumber());
        } catch (Exception $exception) {
            Log::error('ошибка при генерации qr-кода пользователя' . $exception->getMessage());
        }

    }
}
