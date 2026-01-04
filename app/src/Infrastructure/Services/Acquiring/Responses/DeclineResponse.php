<?php

namespace Infrastructure\Services\Acquiring\Responses;

/**
 * @property-read string|null $userMessage
 */
class DeclineResponse extends GatewayResponse
{
    /**
     * @var string|null - Сообщение пользователю с описанием кода результата
     */
    protected ?string $userMessage = null;
}
