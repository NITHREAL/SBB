<?php

namespace Domain\Story\Requests;

use Infrastructure\Http\Requests\BaseRequest;

class StoryMetadataRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'viewDate'      => 'required|date',
            'viewDuration'  => 'required|string',
            'wasClicked'    => 'required|boolean',
            'movedToNext'   => 'required|boolean',
        ];
    }
}
