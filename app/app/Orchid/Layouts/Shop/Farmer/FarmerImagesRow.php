<?php

namespace App\Orchid\Layouts\Shop\Farmer;

use App\Orchid\Fields\Matrix;
use Domain\Farmer\Models\Farmer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Layouts\Rows;

class FarmerImagesRow extends Rows
{

    protected ?Farmer $farmer;

    public function __construct(Farmer $farmer)
    {
        $this->farmer = $farmer;
    }

    protected function fields(): array
    {
        return [
            Matrix::make('farmer.images')
                ->value($this->farmer->images)
                ->columns([
                    'Изображение'   => 'url',
                ])
                ->fields([
                    'url' => Picture::make('url')
                        ->groups('farmer')
                        ->path('farmer')
                        ->targetId()
                        ->required(),
                ]),
        ];
    }
}
