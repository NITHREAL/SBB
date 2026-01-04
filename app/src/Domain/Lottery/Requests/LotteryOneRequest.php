<?php

namespace Domain\Lottery\Requests;

use Infrastructure\Http\Requests\BaseRequest;

class LotteryOneRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'productsLimit' => 'integer|min:1|max:50',
        ];
    }
}
