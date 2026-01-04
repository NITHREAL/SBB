<?php

namespace App\Orchid\Layouts\Shop\Farmer;

use App\Orchid\Helpers\Sight\ActiveSight;
use App\Orchid\Helpers\Sight\DateTimeSight;
use App\Orchid\Helpers\Sight\Id1CSight;
use App\Orchid\Helpers\Sight\IdSight;
use App\Orchid\Helpers\Sight\ImagesSight;
use App\View\Components\Images;
use Domain\Farmer\Models\Farmer;
use Domain\Product\Models\Product;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Legend;
use Orchid\Screen\Sight;
use Orchid\Support\Blade;

class FarmerInfoRow extends Legend
{
    protected $target = 'farmer';

    protected function columns(): array
    {
        return [
            IdSight::make(),
            Id1CSight::make(),
            ActiveSight::make(),
            Sight::make('name', __('admin.farmer.name')),
            Sight::make('slug', __('admin.slug'))
                ->render(function (Farmer $farmer) {
                    return '<input class="form-control" type="text" max="255" placeholder="' .
                        __('admin.slug') .
                        ' " value="' . $farmer->slug . '" name="farmer[slug]" />';
                }),
            Sight::make('sort', __('admin.sort'))
                ->render(function (Farmer $farmer) {
                    return '<input class="form-control" type="number" min="0" placeholder="' .
                        __('admin.sort') .
                        ' " value="' . $farmer->sort . '" name="farmer[sort]" />';
                }),
            Sight::make('supply_description', __('admin.farmer.supply_description')),
            Sight::make('description', __('admin.description')),
            DateTimeSight::createdAt(),
            DateTimeSight::updatedAt(),
        ];
    }
}
