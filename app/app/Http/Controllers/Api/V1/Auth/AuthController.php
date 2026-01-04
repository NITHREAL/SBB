<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Domain\MobileVersion\Enums\MobileVersionPlatformEnum;
use Domain\MobileVersion\Enums\MobileVersionStatusEnum;
use Domain\MobileVersion\Models\MobileVersion;
use Domain\User\DTO\UserAuthDTO;
use Domain\User\Requests\Auth\UserAuthRequest;
use Domain\User\Requests\Auth\UserCheckAuthRequest;
use Domain\User\Resources\Auth\AuthResource;
use Domain\User\Resources\Auth\TokenResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;
use Infrastructure\Http\Responses\Auth\LoginResponse;
use Infrastructure\Services\Auth\Exceptions\AuthenticationException;
use Infrastructure\Services\Auth\Exceptions\SmsCodeAlreadySent;
use Infrastructure\Services\Auth\Login\AuthenticationService;
use Infrastructure\Services\Auth\Login\LoginService;
use Infrastructure\Services\Auth\Logout\LogoutService;
use Knuckles\Scribe\Attributes as SA;
use Symfony\Component\HttpFoundation\Response;

#[
    SA\Group('v1'),
    SA\Subgroup('аутентификация')
]
class AuthController extends Controller
{
    #[
        SA\Endpoint(
            title: 'регистрация/авторизация пользователя по номеру телефона',
            description: 'отправляет sms на указанный номер телефона пользователя с кодом подтверждения'
        ),
        SA\Response(content: '', status: Response::HTTP_OK, description: 'ok'),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'conflict'),
    ]
    /**
     * @throws SmsCodeAlreadySent
     */
    public function login(
        UserAuthRequest $request,
        LoginService $loginService,
    ): JsonResponse {
        $phone = Arr::get($request->validated(), 'phone');

        $signature = $loginService->processLogin($phone);

        return LoginResponse::handle(compact('signature'));
    }

    #[
        SA\Endpoint(
            title: 'проверка кода из смс',
            description: 'проверяет присланный пользователем код из смс и в случае успешной проверки - выдаёт токен'
        ),
        SA\ResponseFromApiResource(
            name: AuthResource::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'conflict'),
    ]
    /**
     * @throws AuthenticationException
     */
    public function codeCheck(
        UserCheckAuthRequest $request,
        AuthenticationService $authenticationService,
    ): JsonResponse {
        $loginDataDTO = UserAuthDTO::make($request->validated());

        $tokenData = $authenticationService->processAuthentication($loginDataDTO);

        return ApiResponse::handle(AuthResource::make($tokenData));
    }

    #[
        SA\Endpoint(title: 'logout пользователя', authenticated: true),
        SA\Response(content: '', status: Response::HTTP_NO_CONTENT, description: 'ok'),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'conflict'),
    ]
    /**
     * @throws AuthenticationException
     */
    public function logout(
        Request $request,
        LogoutService $logoutService
    ): JsonResponse {
        $logoutService->processLogout($request->bearerToken());

        return ApiResponse::handleNoContent();
    }

    #[
        SA\Endpoint(
            title: 'обновление токена пользователя',
            description: 'обновляет токен пользователя',
            authenticated: true,
        ),
        SA\ResponseFromApiResource(
            name: TokenResource::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'conflict'),
    ]
    public function refreshToken(
        Request $request,
        AuthenticationService $authenticationService,
    ): JsonResponse {
        $tokenData = $authenticationService->processTokenRefresh(
            $request->bearerToken(),
            $request->get('refreshToken'),
        );

        return ApiResponse::handle(TokenResource::make($tokenData));
    }

    #[
        SA\Endpoint(title: 'проверка залогинен пользователь или нет'),
        SA\Response(
            content: [
                'isValid' => true
            ],
            status: Response::HTTP_OK,
        ),
    ]
    public function checkToken(): JsonResponse
    {
        return ApiResponse::handle([
            'isValid' => Auth::check(),
        ]);
    }

    #[
        SA\Endpoint(
            title: 'проверка требований по версиям мобильного приложения',
            description: 'отдаёт мп версии принудительного/рекомендуемого обновлений для каждой платформы'
        ),
        SA\Response(
            content: [
                'android' => [
                    'suggestedUpdate' => '1.0.29',
                    'forcedUpdate' => '1.0.29',
                ],
                'ios' => [
                    'suggestedUpdate' => '1.0.29',
                    'forcedUpdate' => '1.0.29',
                ],
            ],
            status: Response::HTTP_OK,
        ),
    ]
    public function checkVersion(): JsonResponse
    {
        $platforms = MobileVersionPlatformEnum::toValues();
        $data = [];

        foreach ($platforms as $platform) {
            $data[$platform] = [
                'suggestedUpdate' => MobileVersion::where([
                    'platform' => $platform,
                    'status' => MobileVersionStatusEnum::recommend()
                ])->first()?->version,
                'forcedUpdate' => MobileVersion::where([
                    'platform' => $platform,
                    'status' => MobileVersionStatusEnum::need_update()
                ])->first()?->version,
            ];
        }
        return response()->json(
            $data
        );
    }
}
