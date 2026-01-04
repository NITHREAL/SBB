<?php

namespace Infrastructure\Http\Requests;

abstract class PaginatedRequest extends BaseRequest
{
    private int $minPerPage = 1;

    private int $maxPerPage = 50;

    public function rules(): array
    {
        return [
            'limit' => "integer|min:{$this->minPerPage}|max:{$this->maxPerPage}",
            'page'  => "integer|min:1",
        ];
    }
}
