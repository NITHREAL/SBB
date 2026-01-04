<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Infrastructure\Services\Auth\RateLimiter\LoginRateLimiter;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class AuthRateLimiter
{
    public function __construct(
        protected LoginRateLimiter $loginRateLimiter,
    ) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->loginRateLimiter->attempt()) {
            throw new TooManyRequestsHttpException();
        }

        return $next($request);
    }
}
