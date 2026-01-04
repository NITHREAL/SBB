<?php

namespace Infrastructure\Services\SMS;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Infrastructure\Notifications\Sms\VerifySmsCode;
use Infrastructure\Services\Auth\Exceptions\SmsCodeAlreadySent;
use Infrastructure\Services\Auth\Exceptions\SmsNotifyException;
use Infrastructure\Services\Auth\Helpers\SmsCodeHelper;
use Infrastructure\Services\SMS\Sender\SmsRu\Exceptions\CouldNotSendNotification;

class SmsCodeService
{
    protected string $codeCacheKeyPrefix = 'sms_ru';

    protected int $codeCacheTtl = 60;

    /**
     * @throws Exception
     */
    public function processCodeSend(string $phone): string
    {
        if ($this->getSmsCodeCacheValue($phone)) {
            throw new SmsCodeAlreadySent;
        }

        $code = $this->sendCode($phone);

        $this->setSmsCodeCacheValue($phone, $code);

        return $code;
    }

    public function processTechCodeSend(string $phone): string
    {
        $code = SmsCodeHelper::generateTechCode($phone);

        $this->setSmsCodeCacheValue($phone, $code);

        return $code;
    }

    public function getSmsCodeCacheValue(string $phone): ?string
    {
        $data = Cache::get($this->getSmsCodeCacheKey($phone));

        return Arr::get($data, 'code');
    }

    /**
     * Генерация и отправка кода
     *
     * @throws Exception
     */
    private function sendCode(string $phone): string
    {
        $code = SmsCodeHelper::generateCode();

        try {
            Notification::route('sms', sprintf('7%s', $phone))->notify(new VerifySmsCode($code));
        } catch (CouldNotSendNotification $exception) {
            throw new SmsNotifyException($exception->getMessage());
        }

        return $code;
    }

    private function getSmsCodeCacheKey(string $phone):  string
    {
        return sprintf('%s_%s', $this->codeCacheKeyPrefix, md5($phone));
    }

    private function setSmsCodeCacheValue(string $phone, string $code): void
    {
        Cache::put(
            $this->getSmsCodeCacheKey($phone),
            compact('phone', 'code'),
            $this->codeCacheTtl,
        );
    }
}
