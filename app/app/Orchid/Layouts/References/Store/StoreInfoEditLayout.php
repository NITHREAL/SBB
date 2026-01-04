<?php

namespace App\Orchid\Layouts\References\Store;

use App\Orchid\Fields\Matrix;
use App\Orchid\Helpers\Fields\CityField;
use Domain\Store\Enums\StoreContactTypeEnum;
use Domain\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class StoreInfoEditLayout extends Rows
{
    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        return [
            Label::make('store.id')
                ->title(__('admin.id'))
                ->horizontal(),

            Label::make('store.system_id')
                ->title(__('admin.system_id'))
                ->horizontal(),

            $this->getActiveField(),

            CheckBox::make('store.is_dark_store')
                ->name('store.is_dark_store')
                ->title(__('admin.store.is_dark_store'))
                ->sendTrueOrFalse()
                ->horizontal(),

            CityField::make('store.city_id')
                ->title(__('admin.store.locality')),

            Input::make('store.title')
                ->title(__('admin.title'))
                ->required()
                ->horizontal(),

            Input::make('store.sort')
                ->title(__('admin.sort'))
                ->type('number')
                ->horizontal(),

            Input::make('store.address')
                ->title(__('admin.address'))
                ->required()
                ->horizontal(),

           Matrix::make('store.contacts')
               ->title(__('admin.store.contacts'))
               ->columns([
                   '' => 'id',
                   'Email/Телефон' => 'type',
                   'Значение' => 'value',
               ])
               ->fields([
                   'id' => Input::make('store.contacts.*.id')
                       ->style('width: 0; padding: 0; margin: 0;')
                       ->type('hidden'),
                   'type' => Select::make('store.contacts.*.type')
                       ->options(StoreContactTypeEnum::toArray())
                       ->required(),
                   'value' => Input::make('store.contacts.*.value')
                       ->type('text')
                       ->required()
               ])
               ->horizontal()
               ->setFirstCol(true),
        ];
    }

    private function getActiveField(): Field|CheckBox
    {
        $activeField = CheckBox::make('store.active')
            ->title(__('admin.active'))
            ->sendTrueOrFalse()
            ->addBeforeRender(function () {
                if ($this->get('disabled')) {
                    $this->set('novalue', $this->get('value') ? 1 : 0);
                }
            })
            ->horizontal();

        /** @var $user User */
        $user = Auth::user();
        if (!$user->hasAccess('activate_store')) {
            $activeField->disabled();
        }

        return $activeField;
    }
}
