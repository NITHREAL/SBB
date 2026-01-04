<?php

namespace App\Http\Controllers\Api\V1\Support;

use App\Http\Controllers\Controller;
use Domain\Support\DTO\SupportMessageDTO;
use Domain\Support\Enums\SupportMessageAuthorEnum;
use Domain\Support\Exceptions\SupportException;
use Domain\Support\Jobs\ReadSupportMessagesJob;
use Domain\Support\Requests\SupportAdminMessageRequest;
use Domain\Support\Requests\SupportMessageReadRequest;
use Domain\Support\Requests\SupportMessageRequest;
use Domain\Support\Resources\SupportMessageResource;
use Domain\Support\Resources\UnreadCountResource;
use Domain\Support\Services\SupportMessageSelectionService;
use Domain\Support\Services\SupportMessageService;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;

class SupportMessageController extends Controller
{
    public function index(SupportMessageSelectionService $supportMessageSelectionService): JsonResponse
    {
        $supportMessages = $supportMessageSelectionService->getUserSupportMessages(Auth::id());

        return ApiResponse::handle(
            SupportMessageResource::collection($supportMessages),
        );
    }

    public function store(
        SupportMessageRequest $request,
        SupportMessageService $supportMessageService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $supportMessageDTO = SupportMessageDTO::make(
            $request->validated(),
            SupportMessageAuthorEnum::user()->value,
            $user->id,
        );

        $supportMessage = $supportMessageService->storeMessage($supportMessageDTO);

        return ApiResponse::handle(
            SupportMessageResource::make($supportMessage),
        );
    }

    public function update(
        int $id,
        SupportMessageService $supportMessageService,
    ): JsonResponse {
        $supportMessage = $supportMessageService->updateMessage($id);

        return ApiResponse::handle(
            SupportMessageResource::make($supportMessage),
        );
    }

    public function read(SupportMessageReadRequest $request,): JsonResponse
    {
        $messageIds = Arr::get($request->validated(), 'messageIds', []);

        ReadSupportMessagesJob::dispatch($messageIds);

        return ApiResponse::handleNoContent();
    }

    public function getUnreadCount(SupportMessageSelectionService $supportMessageSelectionService): JsonResponse
    {
        $count = $supportMessageSelectionService->getUserSupportMessageUnreadCount(Auth::id());

        return ApiResponse::handle(
            UnreadCountResource::make([
                'count' => $count,
            ]),
        );
    }

    /**
     * @throws SupportException
     */
    public function storeAdminMessage(
        SupportAdminMessageRequest $request,
        SupportMessageService $supportMessageService,
    ): JsonResponse {
        $data = $request->validated();
        $userId = Arr::pull($data, 'userId');

        $supportMessageDTO = SupportMessageDTO::make(
            $data,
            SupportMessageAuthorEnum::administrator()->value,
            $userId,
        );

        $supportMessage = $supportMessageService->storeMessage($supportMessageDTO);

        return ApiResponse::handle(
            SupportMessageResource::make($supportMessage)
        );
    }
}
