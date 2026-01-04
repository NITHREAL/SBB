<?php

namespace Domain\Farmer\Accessors;

use Domain\Farmer\Models\Farmer;

final class FarmerReviewInfo
{
    public function __construct(private readonly Farmer $farmer)
    {
    }

    public function __invoke()
    {
        $reviewInfoFormat = [
            "5_star" => 0,
            "4_star" => 0,
            "3_star" => 0,
            "2_star" => 0,
            "1_star" => 0,
        ];

        $reviewInfo = $this->farmer->review_info;

        if (!is_null($reviewInfo)) {
            foreach ($reviewInfo as $key => $item) {
                $reviewInfoFormat[$key.'_star'] = $item;
            }
        }
        krsort($reviewInfoFormat);

        return $reviewInfoFormat;
    }
}
