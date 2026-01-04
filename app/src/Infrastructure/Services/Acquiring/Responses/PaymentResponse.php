<?php

namespace Infrastructure\Services\Acquiring\Responses;

/**
 * @property-read string orderId
 * @property-read string formUrl
 * @property-read array externalParams
 */
class PaymentResponse extends GatewayResponse
{
    /**
     * @var string|null - Номер заказа в платежной системе
     */
    protected ?string $orderId = null;

    /**
     * @var string|null - URL-адрес платёжной формы
     */
    protected ?string $formUrl = null;

    /**
     * @var array|null - Дополнительные параметры в виде пар ключ-значение
     */
    protected ?array $externalParams = null;
}
