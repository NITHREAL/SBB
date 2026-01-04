<?php

namespace App\Orchid\Layouts\Content;

use App\Models\Enums\SlideUserTypesEnum;
use App\Models\Slide;
use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\Active;
use App\Orchid\Helpers\TD\DateTime;
use App\Orchid\Helpers\TD\ID;
use App\Orchid\Helpers\TD\Sort;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SlidesListLayout extends Table
{
    public function __construct(
        protected $route
    )
    {
    }

    protected $target = 'slides';

    protected function columns(): array
    {
        return [
            ID::make(),
            Active::make(),
            TD::make('title', __('admin.title'))->sort(),
            TD::make('user_type', __('admin.slide.user_type'))
                ->render(function (Slide $slide) {
                    return SlideUserTypesEnum::toArray()[$slide->user_type];
                }),

            Sort::make(),

            DateTime::createdAt(),

            TD::make()->actions([
                new Actions\Activate(),
                new Actions\Edit("platform.{$this->route}.edit"),
            ])
        ];
    }
}
