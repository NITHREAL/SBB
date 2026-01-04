<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Exchange;

use Domain\City\Models\City;
use Domain\Exchange\DTO\CityDTO;
use Domain\Exchange\Requests\CityCollectionRequest;
use Domain\Exchange\Requests\CityItemRequest;
use Domain\Exchange\Resources\ResultResource;
use Domain\Exchange\Services\City\CityService;
use Illuminate\Support\Facades\Log;

class CityController extends ExchangeController
{
    public function __construct(
        private readonly CityService $cityService
    ) {
    }

    /**
     * @param CityCollectionRequest $request
     * @return ResultResource
     */
    public function exchangeCollection(CityCollectionRequest $request): ResultResource
    {
        Log::channel('exchange.city')->info('Processing city collection exchange', $request->all());

        return parent::doExchange($request);
    }

    /**
     * @param CityItemRequest $request
     * @return City Возвращает модель City.
     */
    public function exchange(CityItemRequest $request): City
    {
        $data = $request->validated();
        $cityDTO = CityDTO::make($data);

        Log::channel('exchange.city')->info('Processing city item exchange', $data);

        return $this->cityService->exchange($cityDTO);
    }
}
