<?php

namespace App\Orchid\Layouts\Shop\Farmer;

use App\Orchid\Fields\Matrix;
use Domain\Farmer\Models\Farmer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Layouts\Rows;

class FarmerImageRow extends Rows
{

    protected ?Farmer $farmer;

    public function __construct(Farmer $farmer)
    {
        $this->farmer = $farmer;
    }

    protected function fields(): array
    {
        return [
            Matrix::make('farmer.certificates')
                ->value($this->farmer->certificates)
                ->columns([
                    'Id' => 'id',
                    'Описание'      => 'description',
                    'Изображение'   => 'url',
                ])
                ->fields([
                    'id' => Input::make('id')->hidden(),
                    'description' => Input::make('description')->required(),
                    'url' => Picture::make('url')
                        ->groups('certificates')
                        ->path('certificates')
                        ->targetId()
                        ->required(),
                ]),
        ];
    }
}
