<?php

namespace Infrastructure\Services\Acquiring\Gateways;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Infrastructure\Constants\HttpMethods;
use Infrastructure\Services\Acquiring\Gateways\Exceptions\GatewayException;
use Infrastructure\Services\Acquiring\Responses\GatewayResponseInterface;
use Infrastructure\Services\Acquiring\Responses\PaymentResponse;
use Infrastructure\Services\Acquiring\Responses\SberbankResponse;

class Sberbank implements GatewayInterface
{
    private const CURRENCY = 643;

    private const LANGUAGE = 'ru';

    private string $debugUrl      = 'https://ecomtest.sberbank.ru/ecomm/gw/partner/api/v1';

    private string $debugSbpUrl = 'https://ecomift.sberbank.ru/ecomm/gw/partner/api/v1';

    private string $prodSbpUrl = 'https://ecomift.sberbank.ru/ecomm/gw/partner/api/v1';

    private string $productionUrl = 'https://securepayments.sberbank.ru';

    private string $login;

    private string $password;

    private string $debug;

    public function __construct(array $config) {
        $this->login = Arr::get($config, 'login');
        $this->password = Arr::get($config, 'password');
        $this->debug   = Arr::get($config, 'debug') ?? false;
    }

    public function debug(bool $debug = true): Sberbank
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:register
     */
    public function register(array $params): GatewayResponseInterface
    {
        $url = sprintf('%s/%s', $this->getUrl(), 'register.do');

        $response = $this->send($url, $params);

        return new SberbankResponse($response);
    }

    public function registerSbp(array $params): GatewayResponseInterface
    {
        $url = sprintf('%s/%s', $this->getSbpUrl(), 'register.do');

        $response = $this->send($url, $params);

        return new SberbankResponse($response);
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:registerpreauth
     */
    public function registerPreAuth(array $params): GatewayResponseInterface
    {
        $url = sprintf('%s/%s', $this->getUrl(), 'registerPreAuth.do');

        $response = $this->send($url, $params);

        return new SberbankResponse($response);
    }

    /**
     * @param array $params
     * @return PaymentResponse
     * @throws GatewayException
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:paymentorderbinding
     */
    public function registerPreAuthAuto(array $params): GatewayResponseInterface
    {
        $url = sprintf('%s/%s', $this->getUrl(), 'paymentOrderBinding.do');

        $response = $this->send($url, $params);

        return new SberbankResponse($response);
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:deposit
     */
    public function deposit(array $params): GatewayResponseInterface
    {
        $url = sprintf('%s/%s', $this->getUrl(), 'deposit.do');

        $response = $this->send($url, $params);

        return new SberbankResponse($response);
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:refund
     */
    public function refund(array $params): GatewayResponseInterface
    {
        $url = sprintf('%s/%s', $this->getUrl(), 'refund.do');

        $response = $this->send($url, $params);

        return new SberbankResponse($response);
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:reverse
     */
    public function reverse(array $params): GatewayResponseInterface
    {
        $url = sprintf('%s/%s', $this->getUrl(), 'reverse.do');

        $response = $this->send($url, $params);

        return new SberbankResponse($response);
    }


    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:decline
     */
    public function decline(array $params): GatewayResponseInterface
    {
        $url = sprintf('%s/%s', $this->getUrl(), 'decline.do');

        $response = $this->send($url, $params);

        return new SberbankResponse($response);
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:getorderstatusextended
     */
    public function getOrderStatusExtended(array $params): GatewayResponseInterface
    {
        $url = sprintf('%s/%s', $this->getUrl(), 'getOrderStatusExtended.do');

        $response = $this->send($url, $params);

        Log::channel('payment')->info('Ответ эквайринга на получение статуса об оплате. ' . json_encode($response));

        return new SberbankResponse($response);
    }

    private function send(string $url, array $params, string $method = HttpMethods::POST): array
    {
        $params = array_merge(
            $params,
            [
                'userName' => $this->login,
                'password' => $this->password,
            ],
        );

        $curl = curl_init();

        $options = [
            CURLOPT_RETURNTRANSFER      => true,
            CURLOPT_ENCODING            => '',
            CURLOPT_MAXREDIRS           => 10,
            CURLOPT_TIMEOUT             => 60,
            CURLOPT_FOLLOWLOCATION      => true,
            CURLOPT_HTTP_VERSION        => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST       => $method,
            CURLOPT_HTTPHEADER          => [
                'Content-Type: application/json'
            ],
        ];

        if ($method === HttpMethods::GET) {
            $options[CURLOPT_URL] =  sprintf('%s?%s', $url, http_build_query($params));
        } else {
            $options[CURLOPT_URL] = $url;
            $options[CURLOPT_POSTFIELDS] = json_encode($params);
        }

        curl_setopt_array($curl, $options);

        Log::channel('payment')->info(sprintf('Данные по запросу в эквайринг - [%s]', json_encode($options)));

        $response = curl_exec($curl);

        Log::channel('payment')->info(sprintf('Ответ от эквайринга - [%s]', $response));

        if (curl_error($curl)) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. Ошибка - %s',
                    "Ошибка при отправке POST запроса к системе лояльности",
                    curl_error($curl)
                )
            );

            throw new GatewayException(curl_error($curl));
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    private function getUrl(): string
    {
        return $this->debug ? $this->debugUrl : $this->productionUrl;
    }

    private function getSbpUrl(): string
    {
        return $this->debug ? $this->debugSbpUrl : $this->prodSbpUrl;
    }
}
