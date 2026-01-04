<?php

namespace Infrastructure\Services\Acquiring\Responses;

use Domain\Order\Enums\Payment\PaymentStatusEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Infrastructure\Services\Acquiring\Enums\AcquiringTypeEnum;
use Infrastructure\Services\Acquiring\Enums\SberbankPaymentStatusEnum;

class SberbankResponse extends GatewayResponse
{
    protected ?int $id = null;

    /**
     * @var int - Код ошибки
     */
    protected int $errorCode = 0;

    /**
     * @var string|null - Описание ошибки
     */
    protected ?string $errorMessage = null;

    /**
     * @var string - Номер заказа в системе магазина
     */
    protected string $orderNumber;

    /**
     * @var int|null - Состояние заказа в платёжной системе
     */
    protected ?int $orderStatus = null;

    /**
     * @var int - Сумма платежа в минимальных единицах валюты
     */
    protected int $amount;

    /**
     * @var int|null - Код валюты платежа ISO 4217
     */
    protected ?int $currency;

    /**
     * @var string - Дата регистрации заказа в формате UNIX-времени
     */
    protected string $date;

    /**
     * @var string - IP-адрес покупателя
     */
    protected string $ip;

    /**
     * @var array Информация о связке
     */
    protected array $bindingInfo = [];

    protected ?string $formUrl = null;

    protected ?string $orderId = null;

    /**
     * @var string|null - Срок истечения действия карты в формате ГГГГММ
     */
    protected ?string $expiration = null;

    protected ?array $externalParams = null;

    public function payed(): bool
    {
        return in_array(
            $this->orderStatus,
            [SberbankPaymentStatusEnum::hold()->value, SberbankPaymentStatusEnum::deposit()->value],
        );
    }

    public function getPreparedPaymentStatus(): string
    {
        return match((int)$this->getPaymentStatus()) {
            SberbankPaymentStatusEnum::registered()->value  => PaymentStatusEnum::registered()->value,
            SberbankPaymentStatusEnum::hold()->value        => PaymentStatusEnum::hold()->value,
            SberbankPaymentStatusEnum::deposit()->value     => PaymentStatusEnum::deposit()->value,
            SberbankPaymentStatusEnum::reverse()->value     => PaymentStatusEnum::reverse()->value,
            SberbankPaymentStatusEnum::refund()->value      => PaymentStatusEnum::refund()->value,
            SberbankPaymentStatusEnum::initAuth()->value    => PaymentStatusEnum::initAuth()->value,
            SberbankPaymentStatusEnum::decline()->value     => PaymentStatusEnum::decline()->value
        };
    }

    public function getBindingId(): ?string
    {
        return Arr::get($this->bindingInfo, 'bindingId');
    }

    public function getCardInfo(): array
    {
        return $this->cardAuthInfo;
    }

    public function getExternalParams(): ?array
    {
        return $this->externalParams ?? [];
    }

    public function getCardDescription(): ?string
    {
        $cardInfo = $this->getCardInfo();

        return sprintf(
            '%s *%s',
            Arr::get($cardInfo, 'paymentSystem'),
            Str::substr(Arr::get($cardInfo, 'maskedPan'), -4),
        );
    }

    public function getCardData(): array
    {
        $cardInfo = $this->getCardInfo();
        $maskedPan = Arr::get($cardInfo, 'maskedPan');
        $expiration = Arr::get($cardInfo, 'expiration');

        return [
            'firstChars'        => Str::substr($maskedPan, 0, 6),
            'lastChars'         => Str::substr($maskedPan, -4),
            'cardType'          => Arr::get($cardInfo, 'paymentSystem'),
            'expirationMonth'   => Str::substr($expiration, -2),
            'expirationYear'    => Str::substr($expiration, 2, 2),
        ];
    }

    public function getAcquiringType(): string
    {
        return AcquiringTypeEnum::sberbank()->value;
    }

    public function getCardExpiration(): ?string
    {
        return Arr::get($this->getCardInfo(), 'expiration');
    }

    public function getPaymentStatus(): string
    {
        return $this->orderStatus;
    }

    public function getPaymentId(): string
    {
        return $this->orderId;
    }

    public function getOrderSystemId(): string
    {
        return $this->orderNumber;
    }

    public function getFormUrl(): ?string
    {
        return $this->formUrl;
    }

    public function getSbpUrl(): ?string
    {
        return Arr::get($this->getExternalParams(), 'sbolDeepLink');
    }

    public function isError(): bool
    {
        return $this->errorCode > 0;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}
