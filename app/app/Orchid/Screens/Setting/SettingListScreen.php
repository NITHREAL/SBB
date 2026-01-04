<?php

namespace App\Orchid\Screens\Setting;

use App\Orchid\Layouts\Setting\SettingListLayout;
use Illuminate\Http\Request;
use Infrastructure\Setting\Models\Setting;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class SettingListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Настройки';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Ключевые настройки приложения. Обязательно наличие конфигурации for_free_delivery';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'settings' => Setting::filters()->defaultSort('id', 'desc')->paginate(),
        ];
    }

    /**
     * Views.
     *
     * @return string[]|Layout[]
     */
    public function layout(): array
    {
        return [
            SettingListLayout::class,
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make(__('Add'))
                ->icon('plus')
                ->href(route('platform.settings.create')),
        ];
    }

    /**
     * @param Setting $setting
     * @param Request $request
     */
    public function saveUser(Setting $setting, Request $request): void
    {
        $setting->fill($request->input('setting'))
            ->save();

        Toast::info(__('Setting was saved.'));
    }

    /**
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        Setting::findOrFail($request->get('id'))
            ->delete();

        Toast::info(__('Setting was removed'));
    }
}
