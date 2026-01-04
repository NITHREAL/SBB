<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Modules;

use App\Http\Controllers\Controller;
use Domain\Story\DTO\StoryMetadataDTO;
use Domain\Story\Jobs\StoreStoryMetadataJob;
use Domain\Story\Models\Story;
use Domain\Story\Requests\StoryMetadataRequest;
use Domain\Story\Requests\StoryRequest;
use Domain\Story\Resources\StoryResource;
use Domain\Story\Services\StoryService;
use Domain\User\Models\User;
use Illuminate\Http\JsonResponse;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes as SA;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

#[
    SA\Group('v1'),
    SA\Subgroup('модули'),
    SA\Authenticated
]
class StoryController extends Controller
{
    #[
        SA\Endpoint('список сторис', authenticated: true),
        SA\ResponseFromApiResource(
            name: StoryResource::class,
            model: Story::class,
            status: Response::HTTP_OK,
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function index(
        StoryRequest $request,
        StoryService $storyService
    ): JsonResponse {
        $storiesData = $storyService->getList(
            Auth::user()?->id,
            Arr::get($request->validated(), 'limit'),
        );

        return ApiResponse::handle(
            StoryResource::collection($storiesData)
        );
    }


    public function storeMetadata(
        int $storyId,
        StoryMetadataRequest $request,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $storyMetadataDTO = StoryMetadataDTO::make($request->validated(), $storyId, $user);

        StoreStoryMetadataJob::dispatch($storyMetadataDTO);

        return ApiResponse::handleNoContent();
    }
}
