<?php

namespace App\Http\Controllers\Api\V1\User\LoyaltyCards;

use Domain\User\DTO\LoyaltyCards\AddLoyaltyCardDTO;
use Domain\User\Models\User;
use Domain\User\Requests\LoyaltyCards\LoyaltyCardRequest;
use Domain\User\Resources\LoyaltyCard\LoyaltyCardResource;
use Domain\User\Services\LoyaltyCards\LoyaltyCardSelectService;
use Domain\User\Services\LoyaltyCards\LoyaltyCardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;

class LoyaltyCardController
{
    public function index(LoyaltyCardSelectService $loyaltyCardsSelectService): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $cards = $loyaltyCardsSelectService->getUserLoyaltyCards($user);

        return ApiResponse::handle(
            LoyaltyCardResource::collection($cards),
        );
    }

    public function add(
        LoyaltyCardRequest $request,
        LoyaltyCardService  $loyaltyCardsService,
    ): JsonResponse {
        $addLoyaltyCardDTO = AddLoyaltyCardDTO::make(
            array_merge(
                $request->validated(),
                ['user' => Auth::user()],
            ),
        );

        $cards = $loyaltyCardsService->addCard($addLoyaltyCardDTO);

        return ApiResponse::handle(
            LoyaltyCardResource::collection($cards),
        );
    }
}
