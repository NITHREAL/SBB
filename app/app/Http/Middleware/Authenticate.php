<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response as ResponseStatus;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * @param Request $request
     * @param array $guards
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new UnauthorizedHttpException(
            'Пользователь не авторизован',
            'Пользователь не авторизован',
            code: ResponseStatus::HTTP_UNAUTHORIZED
        );
    }
}
