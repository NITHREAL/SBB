<?php

namespace Infrastructure\Services\Acquiring\Gateways;


use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Acquiring\Gateways\Exceptions\GatewayException;
use Infrastructure\Services\Acquiring\Gateways\Exceptions\GatewayInvalidResponseException;
use Infrastructure\Services\Acquiring\Gateways\Exceptions\GatewayNoResponseException;
use Infrastructure\Services\Acquiring\Responses\GatewayResponseInterface;
use Infrastructure\Services\Acquiring\Responses\SberbankResponse;

class SberbankImitation implements GatewayInterface
{
    const SCOPE_REST = 'rest';

    const SCOPE_GOOGLE = 'google';

    const SCOPE_SAMSUNG = 'samsung';

    const SCOPE_APPLE = 'applepay';

    const CURRENCY = 643;

    const LANGUAGE = 'ru';

    const SBER_IMITATION_RESPONSE         = [
        "orderId" => "61351fbd-ac25-484f-b930-4d0ce4101ab7",
        "formUrl" => null,
    ];
    const SBER_IMITATION_RESPONSE_DEPOSIT = [
        "errorCode" => 0,
    ];

    const SBER_IMITATION_RESPONSE_REVERSE = [
        "errorCode"    => "0",
        "errorMessage" => "Успешно",
    ];

    const SBER_IMITATION_RESPONSE_STATUS = [
        "errorCode"             => "0",
        "errorMessage"          => "Успешно",
        "orderNumber"           => "0784sse49d0s134567890",
        "orderStatus"           => 1,
        "actionCode"            => -2007,
        "actionCodeDescription" => "Усешно",
        "amount"                => 33000,
        "currency"              => "643",
        "date"                  => 1383819429914,
        "orderDescription"      => " ",
        "merchantOrderParams"   => [
            [
                "name"  => "email",
                "value" => "yap",
            ],
        ],
        "attributes"            => [
            [
                "name"  => "mdOrder",
                "value" => "b9054496-c65a-4975-9418-1051d101f1b9",
            ],
        ],
        "bindingInfo"           => [
            "clientId"  => 1,
            "bindingId" => 12,
        ],
        "cardAuthInfo"          => [
            "expiration"     => "201912",
            "cardholderName" => "Ivan",
            "secureAuthInfo" => [
                "eci"         => 6,
                "threeDSInfo" => [
                    "xid" => "MDAwMDAwMDEzODM4MTk0MzAzMjM=",
                ],
            ],
            "pan"            => "411111**1111",
        ],
        "terminalId"            => "333333",
    ];

    private string $debugUrl      = 'https://3dsec.sberbank.ru';

    private string $productionUrl = 'https://securepayments.sberbank.ru';

    public function __construct(
        private readonly ?string $login,
        private readonly ?string $password,
        private ?bool            $debug,
    ) {}

    public function debug(bool $debug = true): SberbankImitation
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:decline
     */
    public function decline(array $params): GatewayResponseInterface
    {
        return new SberbankResponse(self::SBER_IMITATION_RESPONSE);
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:deposit
     */
    public function deposit(array $params): GatewayResponseInterface
    {
        return new SberbankResponse(self::SBER_IMITATION_RESPONSE_DEPOSIT);
    }

    public function getClientBindings(array $params): GatewayResponseInterface
    {
        return new SberbankResponse(self::SBER_IMITATION_RESPONSE);
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:getorderstatusextended
     */
    public function getOrderStatusExtended(array $params): GatewayResponseInterface
    {
        return new SberbankResponse(self::SBER_IMITATION_RESPONSE_STATUS);
    }

    /**
     * @param array $params
     *
     * @return GatewayResponseInterface
     * @throws GatewayException
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:paymentorderbinding
     */
    public function paymentOrderBinding(array $params): GatewayResponseInterface
    {
        return new SberbankResponse(self::SBER_IMITATION_RESPONSE);
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:refund
     */
    public function refund(array $params): GatewayResponseInterface
    {
        return new SberbankResponse(self::SBER_IMITATION_RESPONSE);
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:register
     */
    public function register(array $params): GatewayResponseInterface
    {
        return new SberbankResponse(self::SBER_IMITATION_RESPONSE);
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:registerpreauth
     */
    public function registerPreAuth(array $params): GatewayResponseInterface
    {
        return new SberbankResponse(self::SBER_IMITATION_RESPONSE);
    }

    /**
     * @see https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:reverse
     */
    public function reverse(array $params): GatewayResponseInterface
    {
        return new SberbankResponse(self::SBER_IMITATION_RESPONSE_REVERSE);
    }

    /**
     * @throws GatewayNoResponseException
     * @throws GatewayInvalidResponseException
     * @throws GatewayException
     */
    private function send(string $action, array $params, string $scope = self::SCOPE_REST): array
    {
        $url = $this->debug ? $this->debugUrl : $this->productionUrl;

        $params = http_build_query(array_merge($params, [
            'userName' => $this->login,
            'password' => $this->password,
            'language' => self::LANGUAGE,
            'currency' => self::CURRENCY,
        ]));

        $options = [
            CURLOPT_URL            => "$url/payment/$scope/$action?$params",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            ],
        ];

        $curl = curl_init();
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        if (curl_error($curl)) {
            throw new GatewayException(curl_error($curl));
        }

        curl_close($curl);

        return $this->parseResponse($response);
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
            Log::channel('payment')->error(
                sprintf(
                    '%s. Ошибка - %s',
                    "Ошибка при отправке POST запроса к эквайрингу во время холдирования по связке",
                    json_last_error_msg()
                )
            );

            throw new GatewayInvalidResponseException('Ошибка при парсинге ответа эквайринга');
        }

        return $arrayResponse;
    }

    /**
     * @throws GatewayInvalidResponseException
     * @throws GatewayException
     * @throws GatewayNoResponseException
     */
    private function sendPost(string $action, array $params, string $scope = self::SCOPE_REST): array
    {
        $url = $this->getUrl();

        $params = http_build_query(array_merge(
            $params,
            [
                'userName' => $this->login,
                'password' => $this->password,
                'language' => self::LANGUAGE,
                'currency' => self::CURRENCY,
            ]
        ));

        $options = [
            CURLOPT_URL            => "$url/payment/$scope/$action",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            ],
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $params,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        if (curl_error($curl)) {
            Log::channel('payment')->error(
                sprintf(
                    '%s. Ошибка - %s',
                    "Ошибка при отправке POST запроса к эквайрингу во время холдирования по связке",
                    curl_error($curl)
                )
            );

            throw new GatewayException(curl_error($curl));
        }

        curl_close($curl);

        return $this->parseResponse($response);
    }

    private function getUrl(): string
    {
        return $this->debug ? $this->debugUrl : $this->productionUrl;
    }
}
