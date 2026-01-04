<?php

namespace App\Http\Controllers\Api\V1\Basket;

use App\Http\Controllers\Controller;
use Domain\Basket\DTO\ClearBasketDTO;
use Domain\Basket\DTO\Delivery\BasketsDeliveryDTO;
use Domain\Basket\DTO\SetBasketCountDTO;
use Domain\Basket\Exceptions\BasketDeliveryException;
use Domain\Basket\Exceptions\BasketProductException;
use Domain\Basket\Requests\ClearBasketRequest;
use Domain\Basket\Requests\SetBasketCountRequest;
use Domain\Basket\Requests\SetBasketDeliveryRequest;
use Domain\Basket\Requests\SetPromocodeRequest;
use Domain\Basket\Resources\BasketDataResource;
use Domain\Basket\Services\BasketService;
use Domain\Basket\Services\DeliveryParams\BasketDeliveryService;
use Domain\Order\Services\Delivery\Exceptions\DeliveryTypeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Infrastructure\Http\Responses\ApiResponse;

class BasketController extends Controller
{
    public function getBasket(BasketService $basketService,): JsonResponse
    {
        $basketData = $basketService->getBasket();

        return ApiResponse::handle(
            BasketDataResource::make($basketData),
        );
    }

    /**
     * @throws BasketProductException
     */
    public function addProduct(
        int $id,
        BasketService $basketService,
    ): JsonResponse {
        $basketData = $basketService->addProduct($id);

        return ApiResponse::handle(
            BasketDataResource::make($basketData),
        );
    }

    public function removeProduct(
        int $id,
        BasketService $basketService,
    ): JsonResponse {
        $basketData = $basketService->removeProduct($id);

        return ApiResponse::handle(
            BasketDataResource::make($basketData),
        );
    }

    /**
     * @throws BasketProductException
     */
    public function incrementProduct(
        int $productId,
        BasketService $basketService,
    ): JsonResponse {
        $basketData = $basketService->incrementProduct($productId);

        return ApiResponse::handle(
            BasketDataResource::make($basketData),
        );
    }

    /**
     * @throws BasketProductException
     */
    public function setCountProduct(
        SetBasketCountRequest $request,
        BasketService $basketService,
    ): JsonResponse {
        $basketCountDTO = SetBasketCountDTO::make($request->validated());

        $basketData = $basketService->setCountProduct($basketCountDTO);

        return ApiResponse::handle(
            BasketDataResource::make($basketData)
        );
    }

    /**
     * @throws BasketProductException
     */
    public function decrementProduct(
        int $productId,
        BasketService $basketService,
    ): JsonResponse {
        $basketData = $basketService->decrementProduct($productId);

        return ApiResponse::handle(
            BasketDataResource::make($basketData),
        );
    }

    public function clear(
        ClearBasketRequest $request,
        BasketService $basketService,
    ): JsonResponse {
        $clearBasketDTO = ClearBasketDTO::make($request->validated());

        $basketData = $basketService->clear($clearBasketDTO);

        return ApiResponse::handle(
            BasketDataResource::make($basketData)
        );
    }

    /**
     * @throws BasketDeliveryException
     * @throws DeliveryTypeException
     */
    public function setDeliveryParams(
        SetBasketDeliveryRequest $request,
        BasketDeliveryService $basketDeliveryService,
        BasketService $basketService,
    ): JsonResponse {
        $basketDTO = BasketsDeliveryDTO::make($request->validated());

        $deliveryParams = $basketDeliveryService->getPreparedDeliveryParams($basketDTO);

        $basketData = $basketService->setDeliveryParams($deliveryParams);

        return ApiResponse::handle(
            BasketDataResource::make($basketData),
        );
    }

    public function setPromocode(
        SetPromocodeRequest $request,
        BasketService $basketService,
    ): JsonResponse {
        $promocode = Arr::get($request->validated(), 'promocode');

        $basketData = $basketService->setPromocode($promocode);

        return ApiResponse::handle(
            BasketDataResource::make($basketData),
        );
    }

    public function clearPromocode(BasketService $basketService): JsonResponse
    {
        $basketData = $basketService->clearPromocode();

        return ApiResponse::handle(
            BasketDataResource::make($basketData),
        );
    }
}
