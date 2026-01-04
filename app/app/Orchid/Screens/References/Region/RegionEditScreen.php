<?php

namespace App\Orchid\Screens\References\Region;

use Domain\City\Models\Region;
use Domain\City\Requests\Admin\RegionRequest;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class RegionEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавление региона';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = null;

    /**
     * @var bool
     */
    public ?bool $exists = false;

    /**
     * Query data.
     *
     * @param Region $region
     * @return array
     */
    public function query(Region $region): array
    {
        $this->exists = $region->exists;

        if ($this->exists) {
            $this->name = $region->title;
        }

        $region->sort = $region->sort ?? 500;

        return [
            'region' => $region
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make(__('admin.create'))
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make(__('admin.save'))
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make(__('admin.delete'))
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists),
        ];
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('region.title')
                    ->title(__('admin.title'))
                    ->type('text')
                    ->required()
                    ->horizontal(),
                Input::make('region.sort')
                    ->title(__('admin.sort'))
                    ->type('number')
                    ->min(0)
                    ->required()
                    ->help(__('admin.help.sort'))
                    ->horizontal()
            ])
        ];
    }

    /**
     * @param Region $region
     * @param RegionRequest $request
     * @return RedirectResponse
     */
    public function createOrUpdate(Region $region, RegionRequest $request): RedirectResponse
    {
        $this->exists = $region->exists;
        $data = $request->validated()['region'];

        $region->fill($data)->save();

        Alert::info('Вы успешно ' . ($this->exists ? 'изменили' : 'создали') . ' регион.');

        return redirect()->route('platform.regions.list');
    }

    /**
     * @param Region $region
     * @return RedirectResponse
     */
    public function remove(Region $region): RedirectResponse
    {
        $region->delete();

        Alert::info('Вы успешно удалили регион.');

        return redirect()->route('platform.regions.list');
    }
}
