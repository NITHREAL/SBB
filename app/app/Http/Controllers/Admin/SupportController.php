<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Domain\Support\Services\SupportMessageSelectionService;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Responses\ApiResponse;

class SupportController extends Controller
{
    public function getUnreadCount(
        SupportMessageSelectionService $supportMessageSelectionService,
    ): JsonResponse {
        $count = $supportMessageSelectionService->getAdminSupportMessagesUnreadCount();

        return ApiResponse::handle([
            'count' => $count,
        ]);
    }
}
