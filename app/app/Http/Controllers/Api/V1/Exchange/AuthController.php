<?php

namespace App\Http\Controllers\Api\V1\Exchange;

use App\Http\Controllers\Controller;
use Domain\Exchange\Requests\LoginRequest;
use Domain\User\Models\User;
use Domain\User\Resources\Auth\TokenResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Services\Auth\Exceptions\AuthenticationException;
use Infrastructure\Services\Auth\Exceptions\JWTException;
use Infrastructure\Services\Auth\Token;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return TokenResource
     * @throws AuthenticationException|JWTException
     */
    public function login(LoginRequest $request): TokenResource
    {
        $ttl = (int)config('auth.exchange_user.ttl');
        $refreshTtl = (int)config('auth.exchange_user.refresh_ttl');
        $credentials = $request->only('email', 'password');

        Auth::attempt($credentials);

        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || !$user->hasAccess(config('platform.permissions.exchange.slug'))) {
            throw new AuthenticationException(null, 'Не верные логин или пароль');
        }

        $tokens = Token::create($user, $ttl, $refreshTtl);

        return TokenResource::make($tokens);
    }

    /**
     * @param Request $request
     * @return TokenResource
     * @throws JWTException|AuthenticationException
     */
    public function refreshToken(Request $request): TokenResource
    {
        $tokens = Token::refresh($request->bearerToken(), $request->get('refresh_token'));

        return TokenResource::make($tokens);
    }
}
