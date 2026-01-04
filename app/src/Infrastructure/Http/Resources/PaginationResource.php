<?php

namespace Infrastructure\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationResource extends JsonResource
{
    public function toArray($request): array
    {
        $result = [];

        if ($this->resource instanceof LengthAwarePaginator) {
            $result = [
                'total'         => $this->resource->total(),
                'currentPage'   => $this->resource->currentPage(),
                'lastPage'      => $this->resource->lastPage(),
                'limit'         => $this->resource->perPage(),
            ];
        }

        return $result;
    }
}
