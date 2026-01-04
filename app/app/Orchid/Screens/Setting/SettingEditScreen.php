<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Setting;

use App\Orchid\Layouts\Setting\SettingEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Infrastructure\Setting\Models\Setting;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SettingEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Настройка приложения';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Редактирование настройки приложения';

    /**
     * @var bool
     */
    private $exist = false;

    /**
     * Query data.
     *
     * @param int|null $settings
     * @return array
     */
    public function query(Setting $setting): array
    {
        $this->exists = $setting->exists;

        if ($this->exists) {
            $this->name = $setting->key;
        }

        return [
            'setting' => $setting,
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
            Button::make(__('Save'))
                ->icon('check')
                ->method('save'),

            Button::make(__('Remove'))
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exist),
        ];
    }

    /**
     * Views.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::block([
                SettingEditLayout::class,
            ]),
        ];
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(Request $request): RedirectResponse
    {
        $settingId = (int) Arr::get($request->route()->parameters(), 'setting', 0);

        $setting = !empty($settingId) ? Setting::findOrFail($settingId) : new Setting();

        if (empty($settingId)) {
            $request->validate([
                'setting.key' => [
                    'required',
                    Rule::unique(Setting::class, 'key')->ignore($setting),
                ],
            ]);
        }

        $setting->fill($request->get('setting'));

        $setting->save();

        Toast::info(__('Setting was saved'));

        return redirect()->route('platform.settings');
    }

    /**
     * @param int $settings
     * @return RedirectResponse
     */
    public function remove(int $settings): RedirectResponse
    {
        $setting = Setting::findOrFail($settings);

        $setting->delete();

        Toast::info(__('Setting was removed'));

        return redirect()->route('platform.settings');
    }
}
