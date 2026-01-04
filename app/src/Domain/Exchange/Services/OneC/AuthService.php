<?php

declare(strict_types=1);

namespace Domain\Exchange\Services\OneC;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;

class AuthService
{
    private Client $client;
    private ?string $accessToken = null;
    private ?string $refreshToken = null;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function authenticate(): bool
    {
        Log::channel('exchange.onec')->info('Authenticating with 1C...');
        try {
            $response = $this->client->post(config('exchange.auth_url'), [
                RequestOptions::JSON => [
                    'login' => config('exchange.login'),
                    'password' => config('exchange.password'),
                ],
                RequestOptions::VERIFY => false,
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                $this->accessToken = $data['access_token'];
                $this->refreshToken = $data['refresh_token'];
                Log::channel('exchange.onec')->info('Authenticated successfully.');
                return true;
            }
        } catch (\Exception $e) {
            Log::channel('exchange.onec')->error('Failed to authenticate: ' . $e->getMessage());
        }
        return false;
    }

    public function refreshToken(): bool
    {
        Log::channel('exchange.onec')->info('Refreshing token...');
        try {
            $response = $this->client->post(config('exchange.refresh_url'), [
                RequestOptions::JSON => [
                    'refresh_token' => $this->refreshToken,
                ],
                RequestOptions::VERIFY => false,
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                $this->accessToken = $data['access_token'];
                $this->refreshToken = $data['refresh_token'];
                Log::channel('exchange.onec')->info('Token refreshed successfully.');
                return true;
            }
        } catch (\Exception $e) {
            Log::channel('exchange.onec')->error('Failed to refresh token: ' . $e->getMessage());
        }
        return false;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }
}
