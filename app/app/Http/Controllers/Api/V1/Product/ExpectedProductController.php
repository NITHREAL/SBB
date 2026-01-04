<?php

namespace App\Http\Controllers\Api\V1\Product;

use App\Http\Controllers\Controller;
use Domain\Product\DTO\Product\ExpectedProductDTO;
use Domain\Product\Services\ExpectedProduct\ExpectedProductChangeServices;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes as SA;

#[
    SA\Group('v1'),
    SA\Subgroup('аутентификация')
]
class ExpectedProductController extends Controller
{
    #[
        SA\Endpoint(
            title: 'привезти ещё',
            description: 'добавляет товар в список требуемых на складе',
        )
    ]
    public function store(
        int $id,
        ExpectedProductChangeServices $expectedProductService
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $expectedProductService->store(
            ExpectedProductDTO::make($id, $user)
        );

        return ApiResponse::handleNoContent();
    }
}
