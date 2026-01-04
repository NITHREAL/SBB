<?php

namespace Infrastructure\Services\Loyalty\Client;

use Infrastructure\Services\Loyalty\Responses\Manzana\ManzanaResponseInterface;

interface ClientInterface
{
    public function send(string $url, array $params, string $method = 'GET'): array;
}
