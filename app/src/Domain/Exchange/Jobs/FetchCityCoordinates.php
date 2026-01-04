<?php

declare(strict_types=1);

namespace Domain\Exchange\Jobs;

use Domain\City\Models\City;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Infrastructure\Services\DaData\DaData;
use Infrastructure\Services\DaData\Exceptions\DaDataException;

class FetchCityCoordinates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected City $city
    ) {
    }

    public function handle()
    {
        try {
            $coordinates = DaData::getCoordinatesByFiasId($this->city->fias_id);

            if ($coordinates) {
                $this->city->latitude = $coordinates['latitude'];
                $this->city->longitude = $coordinates['longitude'];
                $this->city->save();
            }
        } catch (DaDataException $e) {
            Log::error("Failed to fetch coordinates for city {$this->city->id}: " . $e->getMessage());
        }
    }
}
