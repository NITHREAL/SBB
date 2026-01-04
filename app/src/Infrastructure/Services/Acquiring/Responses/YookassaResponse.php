<?php

namespace Infrastructure\Services\Acquiring\Responses;

use Domain\Order\Enums\Payment\PaymentStatusEnum;
use Illuminate\Support\Arr;
use Infrastructure\Services\Acquiring\Enums\AcquiringTypeEnum;
use Infrastructure\Services\Acquiring\Enums\YooKassaPaymentStatusEnum;

class YookassaResponse extends GatewayResponse implements GatewayResponseInterface
{
    private const RESPONSE_TYPE_ERROR = 'error';

    protected string $id;

    protected array $metadata; // Метаданные, которые можно привязать к оплате, передавая с сайта

    protected string $status = '';

    protected array $confirmation = [];

    protected ?string $type = null;

    protected ?string $description = null;

    protected array $payment_method = [];

    protected ?string $errorMessage = null;

    protected int $errorCode = 0;

    public function getPaymentId(): string
    {
        return $this->id;
    }

    public function getOrderSystemId(): string
    {
        return Arr::get($this->metadata, 'order_id');
    }

    public function getPaymentStatus(): string
    {
        return $this->status;
    }

    public function getPreparedPaymentStatus(): string
    {
        return match ($this->getPaymentStatus()) {
            YooKassaPaymentStatusEnum::pending()->value             => PaymentStatusEnum::registered()->value,
            YooKassaPaymentStatusEnum::waitingForCapture()->value   => PaymentStatusEnum::hold()->value,
            YooKassaPaymentStatusEnum::succeeded()->value           => PaymentStatusEnum::deposit()->value,
            YooKassaPaymentStatusEnum::canceled()->value            => PaymentStatusEnum::decline()->value,
            default                                                 => PaymentStatusEnum::error()->value,
        };
    }

    public function getAcquiringType(): string
    {
        return AcquiringTypeEnum::yookassa()->value;
    }

    public function getFormUrl(): ?string
    {
        return Arr::get($this->confirmation, 'confirmation_url');
    }

    public function payed(): bool
    {
        return in_array(
            $this->status,
            [YooKassaPaymentStatusEnum::succeeded()->value, YooKassaPaymentStatusEnum::waitingForCapture()->value],
        );
    }

    public function getBindingId(): ?string
    {
        return Arr::get($this->payment_method, 'id');
    }

    public function getCardInfo(): array
    {
        return Arr::get($this->payment_method, 'card');
    }

    public function getCardDescription(): ?string
    {
        $cardInfo = $this->getCardInfo();

        return sprintf(
            '%s *%s',
            Arr::get($cardInfo, 'card_type'),
            Arr::get($cardInfo, 'last4'),
        );
    }

    public function getCardData(): array
    {
        $cardInfo = $this->getCardInfo();

        return [
            'firstChars'        => Arr::get($cardInfo, 'first6'),
            'lastChars'         => Arr::get($cardInfo, 'last4'),
            'cardType'          => Arr::get($cardInfo, 'card_type'),
            'expirationMonth'   => Arr::get($cardInfo, 'expiry_month'),
            'expirationYear'    => Arr::get($cardInfo, 'expiry_year'),
        ];
    }

    public function getCardExpiration(): ?string
    {
        $cardInfo = $this->getCardInfo();

        return sprintf(
            '%s/%s',
            Arr::get($cardInfo, 'expiry_month'),
            Arr::get($cardInfo,  'expiry_year'),
        );
    }

    public function isError(): bool
    {
        return !empty($this->type) && $this->type === self::RESPONSE_TYPE_ERROR;
    }

    public function getErrorMessage(): ?string
    {
        return $this->isError()
            ? $this->description
            : 'Ошибок нет';
    }

    public function getExternalParams(): array
    {
        return [];
    }
}
