<?php

namespace Infrastructure\Http\Requests\DaData;

use Infrastructure\Http\Requests\BaseRequest;

class DaDataGeolocateRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ];
    }
}
