<?php

namespace App\Orchid\Screens\References\City;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\References\City\CityRow;
use Domain\City\Models\City;
use Domain\City\Models\Region;
use Domain\City\Requests\Admin\CityRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Infrastructure\Enum\Timezone;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class CityEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавление города';

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

    public ?City $city = null;

    /**
     * Query data.
     *
     * @param City $city
     * @return array
     */
    public function query(City $city): array
    {
        $city->load('included_settlements');

        $this->exists = $city->exists;

        if ($this->exists) {
            $this->name = $city->title;
        }

        $city->sort = $city->sort ?? 500;
        $this->city = $city;

        return [
            'city' => $city
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return Actions::make([
            Actions\Save::for($this->city),
            Actions\Delete::for($this->city)
        ]);
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            CityRow::class
        ];
    }

    /**
     * @param City $city
     *
     * @return RedirectResponse
     */
    public function save(City $city, CityRequest $request): RedirectResponse
    {
        $city->load('included_settlements');

        $this->exists = $city->exists;

        $data = $request->validated()['city'];
        $data['timezone'] = TimeZone::toValues()[$data['timezone']];
        $data['fias_id'] = '';

        $settlements = Arr::get($data, 'included_settlements', []);

        $region = Region::query()->findOrFail($data['region']);
        $city->region()->associate($region);

        unset($data['region']);

        $city->fill($data)->save();
        $city->included_settlements()
            ->whereNotIn('id', $settlements)
            ->update(['for_city_id' => null]);

        City::query()
            ->whereIn('id', $settlements)
            ->update([
                'for_city_id' => $city->id
            ]);

        if ($this->exists) {
            Alert::success('Изменния сохранены');
        } else {
            Alert::success("Добавлена запись \"{$city->title}\"");
        }

        return redirect()->route('platform.cities.edit', $city->id);
    }

    /**
     * @param City $city
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete(City $city): RedirectResponse
    {
        $city->delete();

        Alert::info("Удалена запись \"{$city->title}\"");

        return redirect()->route('platform.cities.list');
    }
}
