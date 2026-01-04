<?php

namespace Domain\Order\Services\Sbermarket;

use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class SbermarketCurlService
{
    private string $token;
    private string $url;

    public function __construct ()
    {
        $config = config('api.sbermarket');

        $this->token = Arr::get($config, 'token');
        $this->url = Arr::get($config, 'url');
    }

    /**
     * @param array $data
     * @param string $status
     * @param string $token
     * @return bool|string
     * @throws HttpClientException
     */
    public function send(
        array $data,
        string $status,
    ): string {
        $ch = curl_init();

        $data["event"]["type"] = $status;

        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = $this->getHeaders();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);

        curl_close($ch);

        $json = (array) json_decode($result, true);

        if ($curl_errno > 0 || Arr::get($json, 'code') >= 400) {
            $message = "cURL Error ($curl_errno): $curl_error\n";
            Log::channel('sbermarket')
                ->error(json_encode(['data' => $data, 'result' => $result, 'error' => $message]));
            throw new HttpClientException($message);
        }

        Log::channel('sbermarket')->info(json_encode(['data' => $data, 'result' => $result]));

        return $result;
    }

    private function getHeaders(): array
    {
        return [
            "Content-Type: application/json",
            "Accept: application/json",
            "Client-Token: {$this->token}",
            "Api-Version: 3.0",
        ];
    }
}
