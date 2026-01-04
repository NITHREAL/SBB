<?php

namespace App\Http\Controllers\Api\V1\Mobile;

use App\Http\Controllers\Controller;
use Domain\MobileToken\DTO\MobileAppTokenDTO;
use Domain\MobileToken\Requests\MobileAppTokenRequest;
use Domain\MobileToken\Resources\MobileAppTokenResource;
use Domain\MobileToken\Services\MobileAppTokenService;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;

class MobileAppTokenController extends Controller
{
    public function store(
        MobileAppTokenRequest $request,
        MobileAppTokenService $mobileAppTokenService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $mobileAppTokenDTO = MobileAppTokenDTO::make($request->validated(), $user);

        return ApiResponse::handle(
            MobileAppTokenResource::make($mobileAppTokenService->storeToken($mobileAppTokenDTO))
        );
    }
}
