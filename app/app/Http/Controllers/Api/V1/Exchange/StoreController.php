<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Exchange;

use Domain\Exchange\Requests\StoreCollectionRequest;
use Domain\Exchange\Requests\StoreItemRequest;
use Domain\Exchange\Resources\ResultResource;
use Domain\Store\DTO\Exchange\StoreExchangeDTO;
use Domain\Store\Services\Exchange\StoreExchangeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class StoreController extends ExchangeController
{
    public function __construct(
        private readonly StoreExchangeService $storeExchangeService
    ) {
    }

    public function exchangeCollection(StoreCollectionRequest $request): ResultResource
    {
        Log::channel('exchange.store')->info('Processing store collection exchange', $request->all());
        return parent::doExchange($request);
    }

    public function exchange(StoreItemRequest $request): Model
    {
        $data = $request->validated();
        $storeDTO = StoreExchangeDTO::make($data);

        Log::channel('exchange.store')->info('Processing store item exchange', $data);

        return $this->storeExchangeService->exchange($storeDTO);
    }
}
