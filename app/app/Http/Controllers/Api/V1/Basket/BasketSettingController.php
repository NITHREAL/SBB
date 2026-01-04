<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Basket;

use App\Http\Controllers\Controller;
use Domain\Basket\DTO\BasketSettingDTO;
use Domain\Basket\Requests\Settings\SetBasketSettingRequest;
use Domain\Basket\Resources\BasketSettingsResource;
use Domain\Basket\Services\Settings\BasketSettingsService;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes;

#[
    Attributes\Group("Order Settings")
]
class BasketSettingController extends Controller
{
    public function __construct(
        private readonly BasketSettingsService $orderSettingsService
    ) {
    }

    public function getSettings(
    ): JsonResponse {
        return ApiResponse::handle(
            BasketSettingsResource::make($this->orderSettingsService->getSettings())
        );
    }

    public function setSettings(
        SetBasketSettingRequest $request
    ): JsonResponse {
        $orderSettingDTO = BasketSettingDTO::make($request->validated());

        return ApiResponse::handle(
            BasketSettingsResource::make($this->orderSettingsService->setSettings($orderSettingDTO))
        );
    }
}
