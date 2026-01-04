<?php

namespace Domain\User\Models\Accessors\User;

use Domain\User\Helpers\UserHelper;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Storage;

final readonly class BonusAccountQrCode
{
    public function __construct(
        private User $user,
    ) {
    }

    public function __invoke(): ?string
    {
        $file = UserHelper::getUserQrCodePath($this->user->getCartNumber());

        return Storage::disk('public')->exists($file)
                ? Storage::disk('public')->url($file)
                : null;
    }
}
