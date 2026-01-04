<?php

namespace Infrastructure\Services\Loyalty\Client;

use Illuminate\Support\Facades\Log;
use Infrastructure\Constants\HttpMethods;
use Infrastructure\Services\Acquiring\Gateways\Exceptions\GatewayException;

class ManzanaClient implements ClientInterface
{
    /**
     * @throws GatewayException
     */
    public function send(string $url, array $params, string $method = HttpMethods::GET): array
    {
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
            $args = $this->prepareGetArgs($params);

            $options[CURLOPT_URL] =  sprintf('%s?%s', $url, $args);
        } else {
            $options[CURLOPT_URL] = $url;
            $options[CURLOPT_POSTFIELDS] = json_encode($params);
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

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

    private function prepareGetArgs(array $params): string
    {
        $args = [];

        foreach ($params as $key => $value) {
            $args[] = sprintf("%s='%s'", $key, $value);
        }

        return implode('&', $args);
    }
}
