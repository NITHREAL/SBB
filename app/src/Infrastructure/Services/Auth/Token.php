<?php

namespace Infrastructure\Services\Auth;

use Carbon\Carbon;
use Domain\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Infrastructure\Services\Auth\Exceptions\AuthenticationException;
use Infrastructure\Services\Auth\Exceptions\JWTException;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token as JWTToken;

class Token
{
    /**
     *
     * @param User $user
     * @param int|null $ttl
     * @param int|null $refreshTtl - in minutes
     *
     * @return array
     *
     * @throws JWTException
     */
    public static function create(
        User $user,
        int $ttl = null,
        int $refreshTtl = null
    ): array {
        $ttl = abs($ttl) ?: config('jwt.ttl');
        $refreshTtl = abs($refreshTtl) ?: config('jwt.refresh_ttl');

        $refreshToken = self::generateRefreshToken($user->id);
        $claims = self::getTokenClaims($refreshToken, $user->id);

        $accessToken = self::generateAccessToken($ttl, $claims, $user->id);

        if (!$accessToken) {
            throw new JWTException('Не удалось сгенерировать JWT токен');
        }

        Cache::put("session_$refreshToken", $claims, $refreshTtl);

        return [
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in'    => self::getExpiresIn($ttl),
        ];
    }

    /**
     * @throws JWTException
     * @throws AuthenticationException
     */
    public static function refresh(string $accessToken, string $refreshToken): array
    {
        $session = Cache::get("session_$refreshToken");

        if (empty($session)) {
            throw new AuthenticationException(
                "Сессия не найдена. refreshToken = [{$refreshToken}]"
            );
        }

        try {
            $accessToken = new JWTToken($accessToken);
            $payload = JWTAuth::manager()->decode($accessToken);
        } catch (Throwable $exception) {
            $message = 'Ошибка при обновлении токена. ' . $exception->getMessage();
            Log::error($message);

            throw new JWTException($message);
        }

        $payloadRefreshToken = $payload->get('refresh_token');

        if ($refreshToken !== $payloadRefreshToken) {
            throw new AuthenticationException(
                "Ошибка при обновлении токена. Refresh Token не совпадает {$refreshToken} | {$payloadRefreshToken}"
            );
        }

        self::invalidate($accessToken);

        $user = self::getUserByTokenData($session);

        return self::create($user);
    }

    /**
     * @throws AuthenticationException
     */
    public static function invalidate(string $accessToken): void
    {
        try {
            $token = new JWTToken($accessToken);

            $payload = JWTAuth::manager()->decode($token);
            $refreshToken = $payload->get('refresh_token');

            if (!empty($refreshToken)) {
                Cache::forget("session_{$refreshToken}");
            }
        } catch (Throwable $exception) {
            $message = 'Ошибка при затрании токена. ' . $exception->getMessage();

            Log::error($message);

            throw new AuthenticationException($message);
        }
    }

    private static function getTokenClaims(string $refreshToken, int $userId): array
    {
        return [
            'refresh_token' => $refreshToken,
            'user_agent'    => request()->userAgent(),
            'microtime'     => microtime(),
            'user_id'       => $userId,
        ];
    }

    private static function generateAccessToken(
        int $ttl,
        array $claims,
        int $userId,
    ): string {
        return auth('api')
            ->setTtl($ttl)
            ->claims($claims)
            ->tokenById($userId);
    }

    private static function getUserByTokenData(array $tokenData): User
    {
        $userId = (int) Arr::get($tokenData, 'user_id');

        return User::query()->findOrFail($userId);
    }

    private static function generateRefreshToken(int $userId): string
    {
        return hash('sha256', uniqid(microtime() . $userId . Str::random(), true));
    }

    private static function getExpiresIn(int $ttl): string
    {
        return Carbon::now()->addSeconds($ttl)->getTimestamp();
    }
}
