<?php

namespace Infrastructure\Services\Acquiring\Gateways;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Infrastructure\Services\Acquiring\Responses\YookassaResponse;
use Infrastructure\Services\Acquiring\Gateways\Exceptions\GatewayException;
use Infrastructure\Services\Acquiring\Gateways\Exceptions\GatewayInvalidResponseException;
use Infrastructure\Services\Acquiring\Gateways\Exceptions\GatewayNoResponseException;
use Infrastructure\Services\Acquiring\Responses\GatewayResponseInterface;

class Yookassa implements GatewayInterface
{
    private const METHOD_GET = 'GET';

    private const METHOD_POST = 'POST';

    private string $shopId;

    private string $secretKey;

    private string $apiBaseEndpoint;

    private bool $testMode;

    public function __construct(array $config)
    {
        $this->apiBaseEndpoint = Arr::get($config, 'base_endpoint');
        $this->testMode = Arr::get($config, 'test_mode', false);

        if ($this->testMode) {
            $this->shopId = Arr::get($config, 'test_shop_id');
            $this->secretKey = Arr::get($config, 'test_secret_key');
        } else {
            $this->shopId = Arr::get($config, 'shop_id');
            $this->secretKey = Arr::get($config, 'secret_key');
        }
    }

    public function debug(bool $debug = true): self
    {
        $this->testMode = $debug;

        return $this;
    }

    /**
     * @throws GatewayException
     */
    public function register(array $params): GatewayResponseInterface
    {
        $response = $this->send('payments', $params, self::METHOD_POST);

        return new YooKassaResponse($response);
    }

    /**
     * @throws GatewayException
     */
    public function registerPreAuth(array $params): GatewayResponseInterface
    {
        $response = $this->send('payments', $params, self::METHOD_POST);

        return new YooKassaResponse($response);
    }

    /**
     * Запрос регистрации оплаты с предавторизацией с возможностью автоплатежа
     * Урл для запроса такой же, как и при обычной предавторизации, но содержит идентификатор карты/связки
     *
     * @param array $params
     * @return GatewayResponseInterface
     * @throws GatewayException
     */
    public function registerPreAuthAuto(array $params): GatewayResponseInterface
    {
        $response = $this->send('payments', $params, self::METHOD_POST);

        return new YooKassaResponse($response);
    }

    public function registerSbp(array $params): GatewayResponseInterface
    {
        // TODO: Implement registerSbp() method.
    }

    /**
     * @throws GatewayException
     */
    public function deposit(array $params): GatewayResponseInterface
    {
        $paymentId = Arr::pull($params, 'payment_id');

        $url = sprintf('payments/%s/capture', $paymentId);

        $response = $this->send($url, $params, self::METHOD_POST);

        return new YooKassaResponse($response);
    }

    /**
     * @throws GatewayException
     */
    public function refund(array $params): GatewayResponseInterface
    {
        $response = $this->send('refunds', $params, self::METHOD_POST);

        return new YooKassaResponse($response);
    }

    /**
     *
     * По сути является копией отмены оплаты, так как у YooKassa нет метода для reverse
     * @throws GatewayException
     */
    public function reverse(array $params): GatewayResponseInterface
    {
        $paymentId = Arr::pull($params, 'payment_id');

        $url = sprintf('payments/%s/cancel', $paymentId);

        $response = $this->send($url, $params, self::METHOD_POST);

        return new YooKassaResponse($response);
    }

    /**
     * @throws GatewayException
     */
    public function decline(array $params): GatewayResponseInterface
    {
        $paymentId = Arr::pull($params, 'payment_id');

        $url = sprintf('payments/%s/cancel', $paymentId);

        $response = $this->send($url, $params, self::METHOD_POST);

        return new YooKassaResponse($response);
    }

    /**
     * @throws GatewayException
     */
    public function getOrderStatusExtended(array $params): GatewayResponseInterface
    {
        $paymentId = Arr::pull($params, 'payment_id');

        $url = sprintf('payments/%s', $paymentId);

        $response = $this->send($url);

        return new YooKassaResponse($response);
    }

    /**
     * @throws GatewayException
     */
    private function send(string $url, array $params = [], string $method = self::METHOD_GET): array
    {
        $options = $method === self::METHOD_GET
            ? $this->getOptionsForGet($url, $params)
            : $this->getOptionsForPost($url, $params);

        $curl = curl_init();

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        if (curl_error($curl)) {
            throw new GatewayException(null, curl_error($curl));
        }

        curl_close($curl);

        return $this->parseResponse($response);
    }

    private function getOptionsForGet(string $url, array $params): array
    {
        return [
            CURLOPT_USERPWD         => $this->getPreparedCredentials(),
            CURLOPT_HTTPHEADER      => $this->getHeaders(),
            CURLOPT_URL             => $this->getPreparedUrl($url, $params),
            CURLOPT_RETURNTRANSFER  => true,
        ];
    }

    private function getOptionsForPost(string $url, array $params): array
    {
        $options = [
            CURLOPT_USERPWD         => $this->getPreparedCredentials(),
            CURLOPT_HTTPHEADER      => $this->getHeaders(),
            CURLOPT_URL             => $this->getPreparedUrl($url),
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_POST            => true,
        ];

        if (count($params)) {
            $options[CURLOPT_POSTFIELDS] = json_encode($params);
        }

        return $options;
    }

    private function getPreparedUrl(string $url, array $params = []): string
    {
        $preparedParams = http_build_query($params);

        return count($params)
            ? sprintf(
                '%s/%s?%s',
                $this->apiBaseEndpoint,
                $url,
                $preparedParams
            )
            : sprintf(
                '%s/%s',
                $this->apiBaseEndpoint,
                $url
            );
    }

    private function getPreparedCredentials(): string
    {
        return sprintf('%s:%s', $this->shopId, $this->secretKey);
    }

    private function getHeaders(): array
    {
        $impodenceKeyHeader = sprintf('Idempotence-Key: %s', Str::uuid());

        return [
            $impodenceKeyHeader,
            'Content-Type: application/json'
        ];
    }

    /**
     * @throws GatewayInvalidResponseException
     * @throws GatewayNoResponseException
     */
    private function parseResponse(string $jsonResponse = null): array
    {
        if (!$jsonResponse) {
            throw new GatewayNoResponseException();
        }

        $arrayResponse = json_decode($jsonResponse, true);

        if (json_last_error()) {
            throw new GatewayInvalidResponseException(null, json_last_error_msg());
        }

        return $arrayResponse;
    }
}
