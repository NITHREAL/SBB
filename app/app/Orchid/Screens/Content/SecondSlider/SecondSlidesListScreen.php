<?php

namespace App\Orchid\Screens\Content\SecondSlider;

use App\Orchid\Screens\Content\SlidesListScreen;

class SecondSlidesListScreen extends SlidesListScreen
{
    /**
     * Display header name.
     * @var string
     */
    public $name = 'Баннер второго уровня';
    public string $route = 'second-slider';
    public string $sliderType = 'second';
}
