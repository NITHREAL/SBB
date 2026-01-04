<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Domain\User\DTO\Profile\ProfileDTO;
use Domain\User\Models\User;
use Domain\User\Requests\Profile\ProfileRequest;
use Domain\User\Requests\Profile\UpdatePhoneCheckCodeRequest;
use Domain\User\Requests\Profile\UpdatePhoneRequest;
use Domain\User\Resources\Profile\ProfileResource;
use Domain\User\Services\Profile\ProfileService;
use Domain\User\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes as SA;
use Symfony\Component\HttpFoundation\Response;

#[
    SA\Group('v1'),
    SA\Subgroup('профиль пользователя')
]
class ProfileController extends Controller
{
    #[
        SA\Endpoint(
            title: 'информация профиля пользователя',
            description: 'отдаёт информацию профиля пользователя',
        ),
        SA\ResponseFromApiResource(
            name: ProfileResource::class,
            model: User::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function show(ProfileService $personalDataService): JsonResponse
    {
        $data = $personalDataService->getPersonalData(Auth::user());

        return ApiResponse::handle(
            ProfileResource::make($data),
        );
    }

    #[
        SA\Endpoint(
            title: 'обновление данных пользователя',
            description: 'обновление личных данных пользователя. кроме номера телефона.',
        ),
        SA\ResponseFromApiResource(
            name: ProfileResource::class,
            model: User::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function update(
        ProfileRequest $request,
        ProfileService $personalDataService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();
        $profileDto = ProfileDTO::make($request->validated(), $user);

        $profileData = $personalDataService->updatePersonalData($user, $profileDto);

        return ApiResponse::handle(
            ProfileResource::make($profileData)
        );
    }

    #[
        SA\Endpoint(
            title: 'обновление номера телефона пользователя',
            description: 'обновление номера телефона пользователя',
        ),
        SA\Response(content: '', status: Response::HTTP_OK, description: 'ok'),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function updatePhone(
        UpdatePhoneRequest $request,
        ProfileService     $personalDataService,
    ): JsonResponse {
        $phone = Arr::get($request->validated(), 'phone');

        $signature = $personalDataService->updatePhoneCodeSend($phone);

        return ApiResponse::handle(compact('signature'));
    }

    #[
        SA\Endpoint(
            title: 'проверка кода из смс',
            description: 'проверка кода из смс отправленного на номер телефона пользователя',
        ),
        SA\Response(content: '', status: Response::HTTP_OK, description: 'ok'),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function checkCode(
        UpdatePhoneCheckCodeRequest $request,
        ProfileService              $personalDataService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $phone = $personalDataService->updatePhone(
            $user,
            Arr::get($request->validated(), 'phone')
        );

        return ApiResponse::handle(compact('phone'));
    }

    public function delete(
        UserService $userService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $userService->deleteUser($user);

        return ApiResponse::handleNoContent();
    }
}
