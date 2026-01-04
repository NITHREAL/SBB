<?php

namespace App\Http\Controllers\Api\V1\Delivery;

use App\Http\Controllers\Controller;
use Domain\Order\DTO\Delivery\DeliveryDateTimeSetDTO;
use Domain\Order\DTO\Delivery\DeliveryTypeByCityDTO;
use Domain\Order\DTO\Delivery\DeliveryTypeSetDTO;
use Domain\Order\Requests\Delivery\AvailableDeliveryTypeRequest;
use Domain\Order\Requests\Delivery\DefaultDeliveryTypeRequest;
use Domain\Order\Requests\Delivery\DeliveryDateTimeTypeRequest;
use Domain\Order\Requests\Delivery\DeliveryTypeRequest;
use Domain\Order\Resources\Delivery\DeliveryDataResource;
use Domain\Order\Resources\Delivery\DeliveryDateTimeDataResource;
use Domain\Order\Services\Delivery\DeliveryTypeService;
use Domain\Order\Services\Delivery\Exceptions\DeliveryDateTimeSetException;
use Domain\Order\Services\Delivery\Exceptions\DeliveryTypeException;
use Domain\User\Services\Delivery\UserDeliveryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Infrastructure\Http\Responses\ApiResponse;
use Infrastructure\Services\DaData\Exceptions\DaDataException;

class DeliveryTypeController extends Controller
{
    /**
     * @throws DeliveryTypeException
     */
    public function setDeliveryType(
        DeliveryTypeRequest $request,
        UserDeliveryService  $userDeliveryService,
    ): JsonResponse {
        $deliveryTypeSetDTO = DeliveryTypeSetDTO::make($request->validated());

        $deliveryParams = $userDeliveryService->setDeliveryData($deliveryTypeSetDTO, Auth::id());

        return ApiResponse::handle(
            DeliveryDataResource::make($deliveryParams),
        );
    }

    /**
     * @throws DaDataException
     */
    public function setDeliveryTypeByCity(
        DefaultDeliveryTypeRequest $request,
        UserDeliveryService $userDeliveryService,
    ): JsonResponse {
        $deliveryTypeByCityDTO = DeliveryTypeByCityDTO::make($request->validated());

        $deliveryParams = $userDeliveryService->setDeliveryDataByCity($deliveryTypeByCityDTO);

        return ApiResponse::handle(
            DeliveryDataResource::make($deliveryParams),
        );
    }

    /**
     * @throws DaDataException
     */
    public function getDeliveryType(UserDeliveryService $userDeliveryService): JsonResponse
    {
        $deliveryParams = $userDeliveryService->getCurrentCachedDeliveryData();

        return ApiResponse::handle(
            DeliveryDataResource::make($deliveryParams)
        );
    }

    /**
     * @throws DeliveryTypeException
     */
    public function getAvailableDeliveryType(
        AvailableDeliveryTypeRequest $request,
        DeliveryTypeService $deliveryTypeService,
    ): JsonResponse {
        $deliveryTypeSetDTO = DeliveryTypeSetDTO::make($request->validated());

        $deliveryData = $deliveryTypeService->getAvailableDeliveryTypeData($deliveryTypeSetDTO);

        return ApiResponse::handle(
            DeliveryDataResource::make($deliveryData),
        );
    }

    /**
     * @throws DeliveryDateTimeSetException
     * @throws DaDataException
     */
    public function setDateTimeIntervals(
        DeliveryDateTimeTypeRequest $request,
        UserDeliveryService $userDeliveryService,
    ): JsonResponse {
        $deliveryDateTimeSetDTO = DeliveryDateTimeSetDTO::make($request->validated());

        $deliveryParams = $userDeliveryService->setDateTimeDeliveryData($deliveryDateTimeSetDTO);

        return ApiResponse::handle(
            DeliveryDateTimeDataResource::make($deliveryParams),
        );
    }

    /**
     * @throws DaDataException
     */
    public function getDateTimeIntervals(
        UserDeliveryService $userDeliveryService,
    ): JsonResponse {
        $deliveryParams = $userDeliveryService->getCurrentCachedDeliveryData();

        return ApiResponse::handle(
            DeliveryDateTimeDataResource::make($deliveryParams),
        );
    }
}
