<?php

namespace App\Orchid\Screens\Content\ThirdSlider;

use App\Orchid\Screens\Content\SlidesListScreen;

class ThirdSlidesListScreen extends SlidesListScreen
{
    /**
     * Display header name.
     * @var string
     */
    public $name = 'Баннер второго уровня';
    public string $route = 'third-slider';
    public string $sliderType = 'third';
}
