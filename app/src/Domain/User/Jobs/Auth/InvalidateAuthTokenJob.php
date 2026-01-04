<?php

namespace Domain\User\Jobs\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;

class InvalidateAuthTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private Token $jwtToken,
    ) {
    }

    public function handle(): void
    {
        try {
            $payload = JWTAuth::manager()->decode($this->jwtToken);
            $refreshToken = $payload->get('refresh_token');

            if (!empty($refreshToken)) {
                Cache::forget("session_{$refreshToken}");
            }
        } catch (TokenBlacklistedException $exception) {
            Log::error("Ошибка при затирании токена. AccessToken - {$refreshToken}. " . $exception->getMessage());
        }
    }
}
