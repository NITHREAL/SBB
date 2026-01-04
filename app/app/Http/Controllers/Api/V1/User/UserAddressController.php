<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Domain\User\DTO\Address\UserAddressDTO;
use Domain\User\Models\User;
use Domain\User\Models\UserAddress;
use Domain\User\Requests\Address\UserAddressRequest;
use Domain\User\Resources\Address\EntranceVariantResource;
use Domain\User\Resources\Address\UserAddressResource;
use Domain\User\Services\Addresses\AddressesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;
use Knuckles\Scribe\Attributes as SA;
use Knuckles\Scribe\Attributes\UrlParam;
use Symfony\Component\HttpFoundation\Response;

#[
    SA\Group('v1'),
    SA\Subgroup('адрес пользователя')
]
class UserAddressController extends Controller
{
    #[
        SA\Endpoint(
            title: 'список адресов пользователя',
            description: 'отдаёт список адресов пользователя',
        ),
        SA\ResponseFromApiResource(
            name: UserAddressResource::class,
            model: UserAddress::class,
            status: Response::HTTP_OK,
            collection: true,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function index(
        AddressesService $addressesService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        return ApiResponse::handle(
            UserAddressResource::collection($addressesService->getListByUser($user->id)),
        );
    }

    #[
        SA\Endpoint(
            title: 'список адресов пользователя',
            description: 'отдаёт список адресов пользователя',
        ),
        SA\ResponseFromApiResource(
            name: UserAddressResource::class,
            model: UserAddress::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[UrlParam('id', 'integer', 'ID адреса пользователя', required: true, example: 10)]
    public function show(
        int $id,
        AddressesService $addressesService,
    ): JsonResponse {
        return ApiResponse::handle(
            UserAddressResource::make($addressesService->getOneById($id))
        );
    }

    #[
        SA\Endpoint(
            title: 'добавление адреса пользователя',
            description: 'добавление адреса пользователя',
        ),
        SA\ResponseFromApiResource(
            name: UserAddressResource::class,
            model: UserAddress::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function store(
        UserAddressRequest $request,
        AddressesService $addressesService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();
        $addressDto = UserAddressDTO::make($request->validated(), $user);

        $address = $addressesService->createUserAddress($addressDto);

        return ApiResponse::handle(
            UserAddressResource::make($address),
        );
    }

    #[
        SA\Endpoint(
            title: 'обновление адреса пользователя',
            description: 'обновление информации адреса пользователя',
        ),
        SA\ResponseFromApiResource(
            name: UserAddressResource::class,
            model: UserAddress::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[UrlParam('id', 'integer', 'ID адреса пользователя', required: true, example: 10)]
    public function update(
        int $id,
        UserAddressRequest $request,
        AddressesService $addressesService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();
        $addressDto = UserAddressDTO::make($request->validated(), $user);

        $address = $addressesService->updateUserAddress($id, $addressDto);

        return ApiResponse::handle(
            UserAddressResource::make($address),
        );
    }

    #[
        SA\Endpoint(
            title: 'удаление адреса пользователя',
            description: 'удаление адреса пользователя',
        ),
        SA\ResponseFromApiResource(
            name: UserAddressResource::class,
            model: UserAddress::class,
            status: Response::HTTP_OK,
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    #[UrlParam('id', 'integer', 'ID адреса пользователя', required: true, example: 10)]
    public function destroy(
        int $id,
        AddressesService $addressesService,
    ): JsonResponse {
        /** @var User $user */
        $user = Auth::user();

        $addressesService->deleteUserAddress($id, $user->id);

        return ApiResponse::handleNoContent();
    }

    #[
        SA\Endpoint(
            title: 'список вариантов входа',
            description: 'список вариантов входа для курьера по адресу пользователя',
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function getEntranceVariants(
        AddressesService $addressesService,
    ): JsonResponse {
        $variants = $addressesService->getEntranceVariants();

        return ApiResponse::handle(
            EntranceVariantResource::collection($variants),
        );
    }
}
