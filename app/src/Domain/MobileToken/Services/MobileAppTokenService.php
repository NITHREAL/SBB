<?php

namespace Domain\MobileToken\Services;

use Domain\MobileToken\DTO\MobileAppTokenDTO;
use Domain\MobileToken\Models\MobileAppToken;
use Domain\User\Models\User;

class MobileAppTokenService
{
    public function storeToken(MobileAppTokenDTO $dto): MobileAppToken
    {
        $mobileAppToken = $this->getMobileAppToken($dto->getUser(), $dto->getToken());

        $mobileAppToken = $this->getFilledMobileAppToken($mobileAppToken, $dto);

        $mobileAppToken->save();

        return $mobileAppToken;
    }

    private function getFilledMobileAppToken(MobileAppToken $mobileAppToken, MobileAppTokenDTO $dto): MobileAppToken
    {
        return $mobileAppToken->fill([
            'service'   => $dto->getService(),
            'device'    => $dto->getDevice(),
        ]);
    }

    private function getMobileAppToken(User $user,string $token): MobileAppToken
    {
        $mobileToken = MobileAppToken::firstOrNew(['user_id' => $user->id]);
        $mobileToken->token = $token;

        return $mobileToken;
    }
}
