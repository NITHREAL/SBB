<?php

namespace App\Http\Controllers\Api\V1\Exchange;

use Domain\Exchange\Requests\UnitCollectionRequest;
use Domain\Exchange\Requests\UnitItemRequest;
use Domain\Exchange\Resources\ResultResource;
use Domain\Unit\DTO\Exchange\OneC\UnitDTO;
use Domain\Unit\Services\Exchange\UnitExchangeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UnitController extends ExchangeController
{
    private readonly UnitExchangeService $unitExchangeService;

    public function __construct()
    {
        $this->unitExchangeService = app()->make(UnitExchangeService::class);
    }

    public function exchangeCollection(UnitCollectionRequest $request): ResultResource
    {
        Log::channel('exchange.unit')->info('Processing unit collection exchange', $request->all());
        return $this->doExchange($request);
    }

    public function exchange(UnitItemRequest $request): Model
    {
        $data = $request->validated();

        Log::channel('exchange.unit')->info('Processing unit item exchange', $data);

        $unitDTO = UnitDTO::make($data);

        return $this->unitExchangeService->exchangeUnit($unitDTO);
    }
}
