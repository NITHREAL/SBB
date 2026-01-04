<?php

namespace Infrastructure\Services\Auth\Logout;

use Illuminate\Support\Facades\Auth;
use Infrastructure\Services\Auth\Exceptions\AuthenticationException;
use Infrastructure\Services\Auth\Token;

class LogoutService
{
    /**
     * @throws AuthenticationException
     */
    public function processLogout(string $bearerToken): void
    {
        $this->invalidateToken($bearerToken);

        Auth::logout();

        //TODO добавить логику для синхронизации корзины пользователя
    }

    /**
     * @throws AuthenticationException
     */
    private function invalidateToken(string $bearerToken): void
    {
        Token::invalidate($bearerToken);
    }
}
