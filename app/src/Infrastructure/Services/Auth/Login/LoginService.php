<?php

namespace Infrastructure\Services\Auth\Login;

use Domain\User\Services\Loyalty\Auth\LoyaltyAuthService;
use Infrastructure\Services\Auth\Exceptions\SmsCodeAlreadySent;
use Infrastructure\Services\Auth\Helpers\SmsCodeHelper;
use Infrastructure\Services\Auth\Signature;
use Infrastructure\Services\SMS\SmsCodeService;

class LoginService
{
    private string $signatureName = 'api:v1:auth.code-check';

    private int $signatureTtl = 300;

    public function __construct(
        protected readonly SmsCodeService $smsCodeService,
        private readonly LoyaltyAuthService $loyaltyAuthService,
    ) {
    }

    public function processLogin(string $phone): string
    {
        $code = $this->loginByPhone($phone);

        return $this->createSignature($phone, $code);
    }

    private function loginByEasypass(string $phone): string
    {
        return $this->smsCodeService->processTechCodeSend($phone);
    }

    /**
     * @throws SmsCodeAlreadySent
     * @throws \Exception
     */
    private function loginByPhone(string $phone): string
    {
        $this->loyaltyAuthService->processLoyaltySmsSend($phone);

        return SmsCodeHelper::generateTechCode($phone);
    }

    private function createSignature(string $phone, string $code): string
    {
        return Signature::create(
            $this->signatureName,
            compact('phone', 'code'),
            $this->signatureTtl,
            true,
        );
    }
}
