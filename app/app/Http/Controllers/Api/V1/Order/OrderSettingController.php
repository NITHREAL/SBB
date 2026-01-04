<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Order;

use App\Http\Controllers\Controller;
use Domain\Order\Enums\OrderSetting\UnavailableProductOrderSettingEnum;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes;

#[
    Attributes\Group("Order Settings")
]
class OrderSettingController extends Controller
{
    #[
        Attributes\Endpoint('Get unavailable products settings'),
        Attributes\Response(
            status: 200,
            description: 'Return unavailable products settings',
        )
    ]
    public function productMissing(): JsonResponse
    {
        return ApiResponse::handle(
            UnavailableProductOrderSettingEnum::getValues()
        );
    }
}
