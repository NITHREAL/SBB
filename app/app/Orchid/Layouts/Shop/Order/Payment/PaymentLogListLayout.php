<?php

namespace App\Orchid\Layouts\Shop\Order\Payment;

use App\Orchid\Helpers\TD\DateTime;
use App\Orchid\Helpers\TD\ID;
use Domain\Order\Models\Payment\OnlinePaymentLog;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PaymentLogListLayout extends Table
{
    protected $target = 'logs';

    protected function columns(): array
    {
        return [
            ID::make(),
            TD::make('method', 'Метод'),
            TD::make('error_code', 'Error code')
                ->render(function (OnlinePaymentLog $log) {
                    $msg = $log->error_code;

                    if ($log->error_message) {
                        $msg .= " ($log->error_message)";
                    }

                    return $msg;
                }),

            TD::make('request', 'Запрос')->render(function (OnlinePaymentLog $log) {
                return
                    '<pre class="json">' .
                        json_encode($log->request, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) .
                    '</pre>'
                ;
            }),

            TD::make('response', 'Ответ')->render(function (OnlinePaymentLog $log) {
                return
                    '<pre class="json">' .
                        json_encode($log->response, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) .
                    '</pre>'
                ;
            }),

            DateTime::createdAt(),
        ];
    }
}
