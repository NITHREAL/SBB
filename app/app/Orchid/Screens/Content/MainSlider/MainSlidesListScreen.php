<?php

namespace App\Orchid\Screens\Content\MainSlider;

use App\Orchid\Screens\Content\SlidesListScreen;

class MainSlidesListScreen extends SlidesListScreen
{
    /**
     * Display header name.
     * @var string
     */
    public $name = 'Баннер на главной странице';
    public string $route = 'main-slider';
    public string $sliderType = 'main';
}
