<?php

namespace Infrastructure\Services\Acquiring\Responses;

use Infrastructure\Services\Acquiring\Gateways\Exceptions\GatewayResponsePropertyNotDefinedException;

/**
 * @property-read int errorCode
 * @property-read string errorMessage
 */
abstract class GatewayResponse implements GatewayResponseInterface
{
    public function __construct(array $response)
    {
        foreach ($response as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @throws GatewayResponsePropertyNotDefinedException
     */
    public function __get(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        $className = get_class($this);
        $message = "Свойство {$name} не определено в классе ответа {$className}";

        throw new GatewayResponsePropertyNotDefinedException( $message);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
