<?php

namespace App\Orchid\Layouts\References\City;

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

class CityRow extends Rows
{
    /**
     * @var string
     */
    protected $template = 'layouts.city_row';

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        return [
            CityField::make('city.title')
                ->title(__('admin.title'))
                ->type('text')
                ->autocomplete('none')
                ->required()
                ->horizontal()
                ->set('dadataRoute', route('api:v1:dadata.cities')),

            Input::make('city.latitude')
                ->title(__('admin.city.latitude'))
                ->formtarget()
                ->horizontal(),

            Input::make('city.longitude')
                ->title(__('admin.city.longitude'))
                ->formtarget()
                ->horizontal(),

            Relation::make('city.included_settlements')
                ->title('Дополнительные населенные пункты')
                ->fromModel(City::class, 'title')
                ->multiple()
                ->horizontal(),

            Relation::make('city.region')
                ->fromModel(Region::class, 'title', 'id')
                ->title(__('admin.city.region'))
                ->required()
                ->horizontal(),

            Select::make('city.timezone')
                ->title(__('admin.city.timezone'))
                ->options(TimeZone::toLabels())
                ->required()
                ->horizontal(),

            Input::make('city.sort')
                ->title(__('admin.sort'))
                ->type('number')
                ->help(__('admin.help.sort'))
                ->required()
                ->horizontal(),

            CheckBox::make('city.is_settlement')
                ->sendTrueOrFalse()
                ->formtarget()
                ->readonly()
                ->style('display: none')
                ->horizontal(),
        ];
    }

    /**
     * @param Repository $repository
     *
     * @return Factory|View
     * @throws Throwable
     */
    public function build(Repository $repository)
    {
        $this->query = $repository;

        if (! $this->isSee()) {
            return;
        }

        $form = new Builder($this->fields(), $repository);

        return view($this->template, [
            'form'  => $form->generateForm(),
            'title' => $this->title,
            'titleName' => $this->getCityTitleName(),
            'fiasIdName' => $this->getCityFiasIdName(),
            'latitudeName' => $this->getLatitudeName(),
            'longitudeName' => $this->getLongitudeName(),
            'isSettlementName' => $this->getIsSettlementName()
        ]);
    }

    protected function getCityTitleName(): string
    {
        return 'city[title]';
    }

    protected function getCityFiasIdName(): string
    {
        return 'city[fias_id]';
    }

    protected function getLatitudeName(): string
    {
        return 'city[latitude]';
    }

    protected function getLongitudeName(): string
    {
        return 'city[longitude]';
    }

    protected function getIsSettlementName(): string
    {
        return 'city[is_settlement]';
    }
}
