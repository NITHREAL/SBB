<?php

namespace Infrastructure\Http\Requests\DaData;

use Infrastructure\Http\Requests\BaseRequest;

class DaDataCityRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'query' => 'required|string',
        ];
    }
}
