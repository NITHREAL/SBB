<?php

namespace Infrastructure\Services\Acquiring\Responses;

interface GatewayResponseInterface
{
    public function getPaymentId(): string;

    public function getPaymentStatus(): string;

    public function getOrderSystemId(): string;

    public function getFormUrl(): ?string;

    public function getSbpUrl(): ?string;

    public function payed(): bool;

    public function getPreparedPaymentStatus(): string;

    public function getBindingId(): ?string;

    public function getCardInfo(): array;

    public function getCardDescription(): ?string;

    public function getCardData(): array;

    public function getCardExpiration(): ?string;

    public function getAcquiringType(): string;

    public function isError(): bool;

    public function getErrorMessage(): ?string;

    public function getExternalParams(): ?array;
}
