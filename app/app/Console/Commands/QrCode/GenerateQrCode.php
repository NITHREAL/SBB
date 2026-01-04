<?php

namespace App\Console\Commands\QrCode;

use Domain\User\Services\UserQrCodeService;
use Illuminate\Console\Command;

class GenerateQrCode extends Command
{
    /**
     * @var string
     */
    protected $signature = 'qrcode:generate_for_users';

    /**
     * @var string
     */
    protected $description = 'Генерация qr-code для пользователей';

    /**
     * Execute the console command.
     * @param UserQrCodeService $userQrCodeService
     * @return void
     */
    public function handle(UserQrCodeService $userQrCodeService): void
    {
        $userQrCodeService->generateMissingQrCode();
    }
}
