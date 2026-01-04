<?php

namespace Domain\Product\Services\Image;

use CurlHandle;
use Exception;

class ImageParsingService
{
    private string $url;
    private CurlHandle $curl;

    public function __construct()
    {
        $this->url = config('image.parsing_url');
        $this->setUpCurl();
    }

    /**
     * @throws Exception
     */
    public function sendRequest(): array
    {
        $response = curl_exec($this->curl);

        if (curl_error($this->curl)) {
            throw new Exception(curl_error($this->curl));
        }

        curl_close($this->curl);

        return $this->responseParser($response);
    }

    private function setUpCurl(): void
    {
        $options = $this->getCurlOptions();

        $this->curl = curl_init();

        curl_setopt_array($this->curl, $options);
    }

    private function getCurlOptions(): array
    {
        return [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ];
    }

    private function responseParser($response): array
    {
        return json_decode($response, true);
    }
}
