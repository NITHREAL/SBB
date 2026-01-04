<?php

namespace Infrastructure\Services\Acquiring\Responses;

use Domain\Order\Enums\Payment\PaymentStatusEnum;
use Illuminate\Support\Arr;

/**
 * @property-read string orderNumber
 * @property-read int orderStatus
 * @property-read int actionCode
 * @property-read string actionCodeDescription
 * @property-read int amount
 * @property-read int currency
 * @property-read string date
 * @property-read int depositDate
 * @property-read string orderDescription
 * @property-read string ip
 * @property-read string authRefNum
 * @property-read string refundedDate
 * @property-read string paymentWay
 * @property-read string avsCode
 * @property-read string expiration
 * @property-read string paymentSystem
 * @property-read string|null maskedPan
 * @property-read array bindingInfo
 */
class OrderStatusResponse extends GatewayResponse
{
    /**
     * @var string - Номер заказа в системе магазина
     */
    protected string $orderNumber;

    /**
     * @var int|null - Состояние заказа в платёжной системе
     */
    protected ?int $orderStatus = null;

    /**
     * @var int - Код ответа процессинга
     */
    protected int $actionCode;

    /**
     * @var string - Расшифровка кода процессинга
     */
    protected string $actionCodeDescription;

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
     * @var int|null - Описание заказа в свободной форме
     */
    protected ?int $depositedDate;

    /**
     * @var string|null - Описание заказа
     */
    protected ?string $orderDescription;

    /**
     * @var string - IP-адрес покупателя
     */
    protected string $ip;

    /**
     * @var string|null - Учётный номер авторизации платежа
     */
    protected ?string $authRefNum;

    /**
     * @var string|null - Дата и время возврата средств
     */
    protected ?string $refundedDate;

    /**
     * @var string - Способ совершения платежа
     */
    protected string $paymentWay;

    /**
     * @var string|null - Код ответа AVS-проверки
     */
    protected ?string $avsCode;

    /**
     * @var array Информация о связке
     */
    protected array $bindingInfo = [];

    protected ?string $bindingId;

    /**
     * @var string|null - Маскированный номер карты
     */
    protected ?string $maskedPan = null;

    /**
     * @var string - Наименование платёжной системы
     */
    protected string $paymentSystem;

    /**
     * @var string|null - Срок истечения действия карты в формате ГГГГММ
     */
    protected ?string $expiration = null;

    public function payed(): bool
    {
        return in_array($this->orderStatus, [1, 2]);
    }

    public function status(): string|null
    {
        $statuses = [
            0 => PaymentStatusEnum::registered()->value,
            1 => PaymentStatusEnum::hold()->value,
            2 => PaymentStatusEnum::deposit()->value,
            3 => PaymentStatusEnum::reverse()->value,
            4 => PaymentStatusEnum::refund()->value,
            5 => PaymentStatusEnum::initAuth()->value,
            6 => PaymentStatusEnum::decline()->value
        ];

        return $statuses[$this->orderStatus] ?? null;
    }

    public function getBindingId(): ?string
    {
        return Arr::get($this->bindingInfo, 'bindingId');
    }

    public function getCardInfo(): array
    {
        return $this->cardAuthInfo;
    }

    public function getPaymentId(): string
    {
        // TODO: Implement getPaymentId() method.
    }

    public function getPaymentStatus(): string
    {
        // TODO: Implement getPaymentStatus() method.
    }

    public function getOrderSystemId(): string
    {
        // TODO: Implement getOrderSystemId() method.
    }

    public function getFormUrl(): ?string
    {
        // TODO: Implement getFormUrl() method.
    }

    public function getPreparedPaymentStatus(): string
    {
        // TODO: Implement getPreparedPaymentStatus() method.
    }

    public function getCardDescription(): ?string
    {
        // TODO: Implement getCardDescription() method.
    }

    public function getCardExpiration(): ?string
    {
        // TODO: Implement getCardExpiration() method.
    }

    public function getAcquiringType(): string
    {
        // TODO: Implement getAcquiringType() method.
    }

    public function isError(): bool
    {
        // TODO: Implement isError() method.
    }

    public function getErrorMessage(): ?string
    {
        // TODO: Implement getErrorMessage() method.
    }

    public function getCardData(): array
    {
        // TODO: Implement getCardData() method.
    }

    public function getExternalParams(): ?array
    {
        // TODO: Implement getExternalParams() method.
    }
}
