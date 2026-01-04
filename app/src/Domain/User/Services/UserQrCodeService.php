<?php

namespace Domain\User\Services;

use Domain\User\Helpers\UserHelper;
use Domain\User\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class UserQrCodeService
{
    public function generateQrCode(string $cardNumber): void
    {
        $mockUrl = 'https://fsbox.shop';
        // TODO хархкод для коробки. на реальном проекте вернуть строку с card number
        $qrCode = QrCode::color(255, 255, 255)->backgroundColor(0, 0, 0)->generate($mockUrl);

        $path = UserHelper::getUserQrCodePath($cardNumber);

        Storage::disk('public')->put($path, $qrCode);
    }

    public function generateMissingQrCode(): void
    {
        $users = User::query()
            ->get();

        $users->each(function ($user) {
            if(is_null($user->bonusAccountQrCode)) {
                $cardNumber = $user->getCartNumber();

                $this->generateQrCode($cardNumber);
            }
        });
    }
}

