<?php

namespace Domain\User\Services\Loyalty\Auth;

use Domain\User\Models\User;
use Domain\User\Services\Loyalty\LoyaltyUserDataService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Infrastructure\Services\Auth\Exceptions\AuthenticationException;
use Infrastructure\Services\Loyalty\Facades\Loyalty;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Auth\RequestSMSAuthDTO;
use Infrastructure\Services\Loyalty\RequestDTO\Manzana\Auth\ConfirmSMSAuthDTO;
use Infrastructure\Services\Loyalty\Responses\Manzana\Auth\ConfirmSmsAuthResponse;
use Infrastructure\Services\Loyalty\Responses\Manzana\Auth\RequestSmsAuthResponse;

class LoyaltyAuthService
{
    private string $tokenCacheKeyPrefix = 'user_auth_data';

    private int $authTokenTtl = 60;

    public function __construct(
        private readonly LoyaltyUserDataService $loyaltyUserDataService,
    ) {
    }

    public function processLoyaltySmsSend(string $phone): void
    {
        $requestSmsDTO = RequestSMSAuthDTO::make([
            'phone'     => $phone,
        ]);

        /** @var RequestSmsAuthResponse $response */
        $response = Loyalty::requestSmsAuth($requestSmsDTO);

        $token = $response->getValue();

        $this->setAuthDataToCache($phone, $token);
    }

    /**
     * @throws AuthenticationException
     */
    public function processLoyaltySmsCodeCheck(User $user, string $code): void
    {
        $cachedData = $this->getAuthDataFromCache($user->phone);

        $token = Arr::get($cachedData, 'token');

        if (empty($token)) {
            throw new AuthenticationException('Ошибка во время подтверждения кода из СМС. Пожалуйста, запросите код повторно');
        }

        $confirmSmsDTO = ConfirmSMSAuthDTO::make([
            'token' => $token,
            'code'  => $code,
        ]);

        /** @var ConfirmSmsAuthResponse $response */
        $response = Loyalty::confirmSmsAuth($confirmSmsDTO);

        $sessionId = $response->getSessionId();
        $loyaltyUserId = $response->getId();

        if (empty($sessionId) || empty($loyaltyUserId)) {
            throw new AuthenticationException('Код неверный');
        }

        $this->loyaltyUserDataService->setUserLoyaltyData($user, $sessionId, $loyaltyUserId);
    }

    private function setAuthDataToCache(string $phone, string $token): void
    {
        Cache::put(
            $this->getCacheKey($phone),
            compact('phone', 'token'),
            $this->authTokenTtl,
        );
    }

    private function getAuthDataFromCache(string $phone): array
    {
        return Cache::get($this->getCacheKey($phone)) ?? [];
    }

    private function getCacheKey(string $phone): string
    {
        return sprintf('%s_%s', $this->tokenCacheKeyPrefix, $phone);
    }
}
