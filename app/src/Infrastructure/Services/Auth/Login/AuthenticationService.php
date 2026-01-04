<?php

namespace Infrastructure\Services\Auth\Login;

use Domain\User\DTO\UserAuthDTO;
use Domain\User\Enums\RegistrationTypeEnum;
use Domain\User\Events\AuthenticatedByApi;
use Domain\User\Jobs\GenerateUserQrCodeJob;
use Domain\User\Jobs\LoyaltyCards\BindVirtualLoyaltyCardToUserJob;
use Domain\User\Models\User;
use Domain\User\Services\Loyalty\Auth\LoyaltyAuthService;
use Domain\User\Services\Settings\UserSettingsService;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\Auth\Exceptions\AuthenticationException;
use Infrastructure\Services\Auth\Exceptions\JWTException;
use Infrastructure\Services\Auth\Token;

readonly class AuthenticationService
{
    public function __construct(
        private UserSettingsService $userSettingsService,
        private LoyaltyAuthService  $loyaltyAuthService,
    ) {
    }

    /**
     * Получение/создание пользователя.
     * Генерация токена.
     * Отправка данных пользователя в сэт
     *
     * @var string $phone
     * @var string $url
     * @throws AuthenticationException
     * @return array
     */
    public function processAuthentication(UserAuthDTO $authDTO): array
    {
        try {
            DB::beginTransaction();

            $userData = $this->processUserDataByPhone($authDTO);
            $user = Arr::get($userData, 'user');

            $this->loyaltyAuthService->processLoyaltySmsCodeCheck($user, $authDTO->code);

            $tokenData = $this->createToken($user);
            $tokenData['isUserNew'] = Arr::get($userData, 'isUserNew');

            $user->refresh();

            DB::commit();

            GenerateUserQrCodeJob::dispatch($user);
        } catch (Exception $exception) {
            DB::rollBack();

            Log::error('Ошибка авторизации. ' . $exception->getMessage());

            throw new AuthenticationException($exception);
        }

        event(new AuthenticatedByApi($user));

        return $tokenData;
    }

    /**
     * @throws AuthenticationException
     * @throws JWTException
     */
    public function processTokenRefresh(string $accessToken, string $refreshToken): array
    {
        return Token::refresh($accessToken, $refreshToken);
    }

    private function processUserDataByPhone(UserAuthDTO $authDTO): array
    {
        $isUserNew = false;

        $user = User::query()->wherePhone($authDTO->getPhone())->first();

        if (empty($user)) {
            $user = User::create([
                'phone'             => $authDTO->getPhone(),
                'registration_type' => RegistrationTypeEnum::app()->value,
            ]);

            $isUserNew = true;

            $user->settings()->create();

            BindVirtualLoyaltyCardToUserJob::dispatch($user)->delay(5);
        }

        $this->userSettingsService->updateNewsSubscription($authDTO->getNewsSubscription(), $user);

        return compact('user', 'isUserNew');
    }

    private function createToken(User $user): array
    {
        return Token::create($user);
    }
}
