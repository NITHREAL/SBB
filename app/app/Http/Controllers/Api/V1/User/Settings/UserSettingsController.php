<?php

namespace App\Http\Controllers\Api\V1\User\Settings;

use App\Http\Controllers\Controller;
use Domain\User\DTO\Settings\UserSettingsDTO;
use Domain\User\Models\User;
use Domain\User\Requests\Settings\UserAutoBrightnessRequest;
use Domain\User\Requests\Settings\UserElectronicChecksRequest;
use Domain\User\Requests\Settings\UserSettingsRequest;
use Domain\User\Resources\Settings\UserAutoBrightnessParamResource;
use Domain\User\Resources\Settings\UserElectronicChecksParamResource;
use Domain\User\Resources\Settings\UserSettingsResource;
use Domain\User\Services\Settings\UserSettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;

class UserSettingsController extends Controller
{
    public function index(
        UserSettingsService $userSettingsService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        return ApiResponse::handle(
            UserSettingsResource::make($userSettingsService->getUserSettingsData($user))
        );
    }

    public function update(
        UserSettingsRequest $request,
        UserSettingsService $userSettingsService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $userSettingsDTO = UserSettingsDTO::make($request->validated(), $user);

        return ApiResponse::handle(
            UserSettingsResource::make($userSettingsService->updateUserSettingsData($userSettingsDTO)),
        );
    }

    public function getElectronicChecksParam(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        return ApiResponse::handle(
            UserElectronicChecksParamResource::make(['electronicChecks' => $user->electronic_checks]),
        );
    }

    public function updateElectronicChecksParam(
        UserElectronicChecksRequest $request,
        UserSettingsService $userSettingsService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $electronicChecksParam = $userSettingsService->updateElectronicChecks(
            Arr::get($request->validated(), 'electronicChecks'),
            $user,
        );

        return ApiResponse::handle(
            UserElectronicChecksParamResource::make(['electronicChecks' => $electronicChecksParam]),
        );
    }

    public function getAutoBrightnessParam(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        return ApiResponse::handle(
            UserAutoBrightnessParamResource::make(['autoBrightness' => $user->auto_brightness]),
        );
    }

    public function updateAutoBrightnessParam(
        UserAutoBrightnessRequest $request,
        UserSettingsService $userSettingsService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $autoBrightnessParam = $userSettingsService->updateAutoBrightness(
            Arr::get($request->validated(), 'autoBrightness'),
            $user,
        );

        return ApiResponse::handle(
            UserAutoBrightnessParamResource::make(['autoBrightness' => $autoBrightnessParam]),
        );
    }
}
