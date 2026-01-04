<?php

namespace App\Http\Controllers\Api\V1\Exchange;

use App\Http\Controllers\Controller;
use Domain\Exchange\Requests\CollectionRequest;
use Domain\Exchange\Requests\ItemRequest;
use Domain\Exchange\Resources\ResultResource;
use Domain\Order\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;

abstract class ExchangeController extends Controller
{
    public function doExchange(CollectionRequest $request): ResultResource
    {
        $responseData = [];

        Log::channel('exchange.order')->info('Началась загрузка списка заказов');
        $headers = json_encode(getallheaders());
        $body = json_encode($request->all());
        Log::channel('exchange.order')->info('Заголовки запроса: ' . $headers);
        Log::channel('exchange.order')->info('Тело запроса: ' . $body);

        $request->map(function (ItemRequest $item) use (&$responseData) {
            $result = ['system_id' => $item->get('system_id')];

            if ($item->validate()) {
                try {
                    $model = $this->exchange($item);
                    $result['id'] = $model->id;

                    if ($model instanceof Order) {
                        $result['status'] = $model->status;
                    }
                } catch (Exception $e) {
                    $result['reasons'] = $e->getMessage();
                }
            } else {
                $result['reasons'] = $item->getErrorMessages();
            }

            $responseData[] = $result;
        });

        $response = new ResultResource($responseData);
        Log::channel('exchange.order')->info('Ответ: ' . json_encode($response->toArray(request())));
        Log::channel('exchange.order')->info('Загрузка списка заказов завершена');

        return $response;
    }
}
