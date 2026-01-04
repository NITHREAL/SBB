<?php

declare(strict_types=1);

namespace Domain\Exchange\Services\OneC;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OneCService
{
    private Client $client;
    private AuthService $authService;

    public function __construct(Client $client, AuthService $authService)
    {
        $this->client = $client;
        $this->authService = $authService;
    }

    public function sendData(array $data): ?array
    {
        if (!$this->authService->getAccessToken() && !$this->authService->authenticate()) {
            return null;
        }

        Log::channel('exchange.onec')->info('Sending data to 1C...', $data);

        try {
            $response = $this->client->post(config('exchange.order_url'), [
                RequestOptions::JSON => $data,
                RequestOptions::HEADERS =>  [
                    'Authorization' => 'Bearer ' . $this->authService->getAccessToken(),
                ],
                RequestOptions::VERIFY => false,
            ]);

            if ($response->getStatusCode() === Response::HTTP_OK) {
                $responseBody = $response->getBody()->getContents();
                $decodedResponse = json_decode($responseBody, true);
                Log::channel('exchange.onec')->info('Response from 1C: ' . json_encode($decodedResponse, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                Log::channel('exchange.onec')->info('Data sent to 1C successfully.');
                return $decodedResponse;
            } elseif (in_array($response->getStatusCode(), [Response::HTTP_UNAUTHORIZED, Response::HTTP_FORBIDDEN])) {
                if ($this->authService->refreshToken()) {
                    return $this->sendData($data);
                }
            }
        } catch (\Exception $e) {
            Log::channel('exchange.onec')->error('Failed to send data to 1C: ' . $e->getMessage());
        }

        return null;
    }
}
