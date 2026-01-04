<?php

namespace App\Orchid\Layouts\Setting;

use App\Orchid\Helpers\TD\Active;
use App\Orchid\Core\TD;
use Infrastructure\Setting\Models\Setting;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class SettingListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'settings';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('key', __('admin.settings.key'))
                ->sort()
                ->cantHide(),

            TD::make('value', __('admin.settings.value'))
                ->sort()
                ->cantHide(),

            TD::make('type', __('admin.settings.type'))
                ->sort()
                ->cantHide(),

            TD::make('description', __('admin.settings.description'))
                ->sort(),

            Active::make(),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(function (Setting $setting) {
                    return $setting->updated_at?->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Setting $setting) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.settings.edit', $setting->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->method('remove', [
                                    'id' => $setting->id,
                                ]),
                        ]);
                }),
        ];
    }
}
