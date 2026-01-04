<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

class VerifyAppToken
{
    private const TOKEN_ENCODING_TIME_RANGE = 60;

    private const EXCLUDED_HOSTS = ['127.0.0.1', 'localhost'];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws TokenMismatchException
     */
    public function handle(Request $request, Closure $next)
    {
        // TODO сделать верификацию при помощи rsa ключей
        return $next($request);

        if (in_array(parse_url($request->url(), PHP_URL_HOST), self::EXCLUDED_HOSTS)) {
            return $next($request);
        }

        $sign = $request->header('signed');
        $time = time();
        $params = $request->all();

        ksort($params);

        for ($i = 0; $i <= self::TOKEN_ENCODING_TIME_RANGE; $i++) {
            $checkSign = $this->getSignFromRequestParams($params, $time);

            if ($checkSign === $sign) {
                return $next($request);
            }

            $time--;
        }

        throw new TokenMismatchException('SIGN token mismatch.');
    }

    private function getSignFromRequestParams(array $params, int $time): string
    {
        $preparedParams = [];

        foreach ($params as $k => $v) {
            $preparedParams[] = sprintf('%s=%s', $k, (is_array($v) ? json_encode($v) : $v));
        }

        $sign = sprintf('%s,%s', implode(',', $preparedParams), $time);

        return md5($sign);
    }
}
