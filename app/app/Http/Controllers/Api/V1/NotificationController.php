<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Domain\Notification\Resources\NotificationResource;
use Domain\Notification\Services\NotificationService;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes as SA;
use Knuckles\Scribe\Attributes\UrlParam;
use Symfony\Component\HttpFoundation\Response;

#[
    SA\Group('v1'),
    SA\Subgroup('уведомления пользователей')
]
class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        return ApiResponse::handle(
            NotificationResource::collection($user->notifications),
        );
    }

    public function unread(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        return ApiResponse::handle(
            NotificationResource::collection($user->unreadNotifications),
        );
    }

    #[
        SA\Endpoint(
            title: 'отметить прочитанным уведомление',
            description: 'делает выбранное уведомление прочитанным',
        ),
        SA\Response(content: '', status: Response::HTTP_NO_CONTENT, description: 'ok'),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[UrlParam('id', 'integer', 'ID уведомления', required: true, example: 10)]
    public function readOne(
        string $id,
        NotificationService $notificationService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $notificationService->markReadOne($user, $id);

        return ApiResponse::handleNoContent();
    }

    #[
        SA\Endpoint(
            title: 'отметить все уведомления прочитанными',
            description: 'делает все уведомления прочитанными',
        ),
        SA\Response(content: '', status: Response::HTTP_NO_CONTENT, description: 'ok'),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function readAll(NotificationService $notificationService): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $notificationService->markReadAll($user);

        return ApiResponse::handleNoContent();
    }

    #[
        SA\Endpoint(
            title: 'удалить все уведомления',
            description: 'удаляет все уведомления',
        ),
        SA\Response(content: '', status: Response::HTTP_NO_CONTENT, description: 'ok'),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function removeAll(NotificationService $notificationService): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $notificationService->removeAll($user);

        return ApiResponse::handleNoContent();
    }
}
