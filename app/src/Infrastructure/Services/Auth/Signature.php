<?php

namespace Infrastructure\Services\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class Signature
{
    private const SIGNATURE_CACHE_PREFIX = 'signature';

    /**
     * Get signature params
     */
    public static function get(string $signature): array
    {
        return Cache::get(self::getSignatureKey($signature));
    }

    /**
     * Create signature for a named route
     */
    public static function create(
        string $name,
        array $parameters = [],
        int $ttl = null,
        bool $disposable = false,
    ): string {
        [$signature, $route] = self::getSignatureData($name, $parameters);

        $parameters['route'] = $route;
        $parameters['disposable'] = (int) $disposable;

        self::store($signature, $parameters, $ttl);

        Log::info('create', [
            '$route' => $route,
            'params' => $parameters,
            '$signature' => $signature,
        ]);

        return $signature;
    }

    /**
     * Store signature in Redis
     *
     * @param string $signature
     * @param array $parameters
     * @param int|null $ttl - in seconds
     */
    private static function store(string $signature, array $parameters = [], int $ttl = null): void
    {
        Cache::put(self::getSignatureKey($signature), $parameters, $ttl);
    }

    /**
     * Determine if the given request has a valid signature.
     *
     * @param Request $request
     *
     * @return bool
     */
    public static function validate(
        string $signature,
        string $url,
        array $params,
    ): bool {
        $available = $signature && Cache::get(self::getSignatureKey($signature));

        return $available && self::isCorrect($signature, $url, $params);
    }

    /**
     * Determine if the signature from the given request matches the URL.
     *
     * @param Request $request
     *
     * @return bool
     */
    public static function isCorrect(
        string $signature,
        string $url,
        array $params,
    ): bool {
        ksort($params);

        if (!in_array(parse_url($url, PHP_URL_HOST), ['127.0.0.1', 'localhost'])) {
            $url = preg_replace('/^http:\/\//', 'https://', $url);
        }

        $data = rtrim($url . '?' . http_build_query($params), '?');
        $newSignature = hash_hmac('sha256', $data, self::getKeyResolver());

        return hash_equals($newSignature, $signature);
    }

    public static function invalidate(string $signature): void
    {
        Cache::forget(self::getSignatureKey($signature));
    }

    private static function getSignatureData(
        string $name,
        array $parameters,
    ): array {
        $route = URL::signedRoute($name, $parameters);
        $url = parse_url($route);

        parse_str($url['query'], $params);

        return [
            $params['signature'],
            self::getFormattedRoute($url),
        ];
    }

    private static function getFormattedRoute(array $urlData): string
    {
        return sprintf('%s://%s%s', $urlData['scheme'], $urlData['host'], $urlData['path']);
    }

    private static function getKeyResolver()
    {
        return app()->make('config')->get('app.key');
    }

    private static function getSignatureKey(string $signature): string
    {
        return sprintf('%s_%s', self::SIGNATURE_CACHE_PREFIX, $signature);
    }
}
