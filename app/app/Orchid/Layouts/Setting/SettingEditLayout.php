<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Setting;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class SettingEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('setting.key')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('admin.settings.key'))
                ->help(__('Ключ настройки. Должен быть уникальным')),

            Input::make('setting.value')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('admin.settings.value'))
                ->help(__('Значение настройки')),

            Input::make('setting.type')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('admin.settings.type'))
                ->help('Может быть только integer, string, float, boolean, json'),

            Input::make('setting.description')
                ->type('text')
                ->max(255)
                ->title(__('admin.settings.description')),

            $this->getActiveField(),
        ];
    }

    private function getActiveField(): Field|CheckBox
    {
        $activeField = CheckBox::make('setting.active')
            ->title('admin.active')
            ->sendTrueOrFalse()
            ->addBeforeRender(function () {
                if ($this->get('disabled')) {
                    $this->set('novalue', $this->get('value') ? 1 : 0);
                }
            })
            ->horizontal();


        return $activeField;
    }
}
