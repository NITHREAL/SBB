<?php

namespace App\Orchid\Layouts\Shop\Tag;

use App\Orchid\Screens\Fields\CityField;
use Domain\City\Models\City;
use Domain\City\Models\Region;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Infrastructure\Enum\Timezone;
use Orchid\Screen\Builder;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Repository;
use Throwable;

class TagEditLayout extends Rows
{
    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        $tag = $this->query->get('tag');
        return [

            Input::make('text')
                ->title(__('admin.tag.text'))
                ->type('text')
                ->value($tag->text)
                ->horizontal(),
            Input::make('color')
                ->title(__('admin.tag.color'))
                ->type('color')
                ->value($tag->color)
                ->horizontal(),
            CheckBox::make('active')
                ->title(__('admin.tag.active'))
                ->value($tag->active)
                ->sendTrueOrFalse()
                ->horizontal(),
        ];
    }
}
