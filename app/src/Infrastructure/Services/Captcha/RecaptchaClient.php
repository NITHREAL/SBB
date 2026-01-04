<?php

namespace Infrastructure\Services\Captcha;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class RecaptchaClient implements CaptchaInterface
{
    private string $host;
    private string $secret;

    public function __construct(array $config) {
        $this->host = Arr::get($config, 'host');
        $this->secret = Arr::get($config, 'secret');
    }

    public function send(mixed $value): array
    {
        $response = Http::asForm()->post($this->host, [
            'secret' => $this->secret,
            'response' => $value,
        ]);

        return $response->json();
    }
}
