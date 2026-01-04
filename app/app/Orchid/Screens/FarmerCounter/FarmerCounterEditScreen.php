<?php

namespace App\Orchid\Screens\FarmerCounter;

use App\Http\Requests\FarmerCounterRequest;
use App\Models\Counter;
use App\Orchid\Layouts\FarmerCounter\FarmerCounterEditLayout;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class FarmerCounterEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Счетчик займов фермерам';

    /**
     * @var bool
     */
    public bool $exists = false;

    /**
     * Query data.
     * @param Counter $farmerCounter
     * @return array
     */
    public function query(Counter $farmerCounter): array
    {
        $this->exists = $farmerCounter->exists;

        if ($this->exists) {
            $this->name = 'Редактирование cчетчика';
        }

        return [
            'farmer_counter' => $farmerCounter
        ];
    }

    /**
     * Button commands.
     * @return Action[]
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

            Button::make(__('admin.remove'))
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
            FarmerCounterEditLayout::class,
        ];
    }

    /**
     * @param Counter $farmerCounter
     * @param FarmerCounterRequest $request
     * @return RedirectResponse
     */
    public function createOrUpdate(Counter $farmerCounter, FarmerCounterRequest $request): RedirectResponse
    {
        $this->exists = $farmerCounter->exists;
        $data = $request->validated()['farmer_counter'];
        $data['type'] = 'farmer';
        $farmerCounter->fill($data)->save();

        Alert::info('Вы успешно ' . ($this->exists ? 'изменили' : 'создали') . ' счетчик.');

        return redirect()->route('platform.farmer-counter.list');
    }

    /**
     * @param Counter $farmerCounter
     * @return RedirectResponse
     */
    public function remove(Counter $farmerCounter): RedirectResponse
    {
        $farmerCounter->delete();

        Alert::info('Вы успешно удалили счетчик.');

        return redirect()->route('platform.farmer-counter.list');
    }
}
