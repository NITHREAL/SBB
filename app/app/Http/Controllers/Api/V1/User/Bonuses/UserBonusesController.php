<?php

namespace App\Http\Controllers\Api\V1\User\Bonuses;

use App\Http\Controllers\Controller;
use Domain\User\DTO\Bonuses\BonusesHistoryDTO;
use Domain\User\Models\User;
use Domain\User\Requests\Bonuses\BonusesHistoryRequest;
use Domain\User\Resources\Bonuses\BonusAccountBalancesResource;
use Domain\User\Resources\Bonuses\BonusAccountHistoryListResource;
use Domain\User\Resources\Bonuses\BonusAccountOverviewResource;
use Domain\User\Services\Bonuses\BonusesHistoryService;
use Domain\User\Services\Bonuses\BonusesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;

class UserBonusesController extends Controller
{
    public function getBonusAccountBalances(BonusesService $bonusesService): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $bonusesService->getBonusAccountBalance($user);

        return ApiResponse::handle(
            BonusAccountBalancesResource::make($data),
        );
    }

    public function getBonusAccountOverview(BonusesService $bonusesService): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $data = $bonusesService->getBonusAccountBalanceWithFaq($user);

        return ApiResponse::handle(
            BonusAccountOverviewResource::make($data),
        );
    }

    public function getBonusAccountHistory(
        BonusesHistoryRequest $request,
        BonusesHistoryService $bonusesHistoryService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $bonusesHistoryDTO = BonusesHistoryDTO::make($request->validated(), $user);

        $data = $bonusesHistoryService->getBonusAccountHistory($bonusesHistoryDTO);

        return ApiResponse::handle(
            BonusAccountHistoryListResource::make($data),
        );
    }
}
