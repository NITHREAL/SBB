<?php

namespace Infrastructure\Services\Yandex\AppMetrica;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Infrastructure\Services\Yandex\AppMetrica\Exceptions\AppMetricaException;

class AppMetricaApi
{
    const API_MANAGEMENT_GROUPS = 'https://push.api.appmetrica.yandex.net/push/v1/management/groups';
    const API_SEND_BATCH = 'https://push.api.appmetrica.yandex.net/push/v1/send-batch';

    public function __construct(
        private readonly string $token,
        private readonly string $appId,
        private readonly Client $client,
    ) {
    }

    public function groups(): array
    {
        $options = [
            'query' => [
                'app_id' => $this->appId
            ]
        ];
        return $this->send('GET', static::API_MANAGEMENT_GROUPS, $options);
    }

    public function pushSendBatch(array $params): array
    {
        $options = [
            'body' => json_encode($params)
        ];
        return $this->send('POST', static::API_SEND_BATCH, $options);
    }

    /**
     * @throws GuzzleException
     * @throws AppMetricaException
     */
    protected function send(string $method, string $url, array $options = []): ?array
    {
        $headers = [
            'headers' => [
                'Authorization' => 'OAuth ' . $this->token,
                'Content-Type'  => 'application/json'
            ],
        ];

        $options = array_merge_recursive($headers, $options);

        $response = $this->client->request($method, $url, $options);

        $body = json_decode($response->getBody(), true);

        if ($response->getStatusCode() !== 200) {
            throw new AppMetricaException($body['message']);
        }
        return $body;
    }
}
