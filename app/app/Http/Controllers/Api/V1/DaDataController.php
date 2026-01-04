<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Domain\City\Models\City;
use Domain\City\Resources\CityResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Infrastructure\Http\Requests\DaData\DaDataAddressRequest;
use Infrastructure\Http\Requests\DaData\DaDataCityRequest;
use Infrastructure\Http\Requests\DaData\DaDataGeolocateRequest;
use Infrastructure\Http\Resources\DaData\DaDataAddressResource;
use Infrastructure\Http\Resources\DaData\DaDataCityResource;
use Infrastructure\Http\Responses\ApiResponse;
use Infrastructure\Services\Buyer\Facades\BuyerCity;
use Infrastructure\Services\DaData\Address\DaDataAddressService;
use Infrastructure\Services\DaData\City\DaDataCityService;
use Infrastructure\Services\DaData\Exceptions\DaDataException;
use Knuckles\Scribe\Attributes as SA;
use Symfony\Component\HttpFoundation\Response;

#[
    SA\Group('v1'),
    SA\Subgroup('дадата')
]
class DaDataController extends Controller
{
    public function getCities(
        DaDataCityRequest $request,
        DaDataCityService $cityService,
    ): JsonResponse {
        $query = Arr::get($request->validated(), 'query');

        $citiesData = $cityService->getCitiesData($query);

        return ApiResponse::handle(
            DaDataCityResource::collection($citiesData),
        );
    }

    public function getAddresses(
        DaDataAddressRequest $request,
        DaDataAddressService $addressService,
    ): JsonResponse {
        $data = $request->validated();

        $addressesData = $addressService->getAddressesDataByQuery(
            Arr::get($data, 'query'),
            Arr::get($data, 'uniqueStreets', false),
        );

        return ApiResponse::handle(
            DaDataAddressResource::collection($addressesData),
        );
    }

    /**
     * @throws DaDataException
     */
    public function getAddressByCoords(
        DaDataGeolocateRequest $request,
        DaDataAddressService $addressService,
    ): JsonResponse {
        $data = $request->validated();

        $address = $addressService->getAddressByCoordinates(
            Arr::get($data, 'latitude'),
            Arr::get($data, 'longitude'),
        );

        return ApiResponse::handle(
            DaDataAddressResource::make($address),
        );
    }

    #[
        SA\Endpoint(
            title: 'город пользователя',
            description: 'возвращает выбранный город пользователя',
        ),
        SA\ResponseFromApiResource(
            name: CityResource::class,
            model: City::class,
            status: Response::HTTP_OK
        ),
        SA\Response(content: '', status: Response::HTTP_BAD_REQUEST, description: 'Bad request'),
        SA\Response(content: '', status: Response::HTTP_CONFLICT, description: 'Conflict'),
    ]
    public function getBuyerLocation(): JsonResponse
    {
        $city = BuyerCity::getSelectedCity();

        return ApiResponse::handle(
            CityResource::make($city),
        );
    }
}
