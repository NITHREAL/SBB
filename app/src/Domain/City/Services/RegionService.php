<?php

namespace Domain\City\Services;

use Domain\City\Models\City;
use Domain\City\Models\Region;
use Illuminate\Support\Collection;

class RegionService
{
    public function getAllRegions(): Collection
    {
        return Region::query()
            ->withCount('cities')
            ->whereHas('cities')
            ->orderBy('sort')
            ->get();
    }
}
