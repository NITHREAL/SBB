<?php

namespace App\Orchid\Screens\Shop\ProductFilter;

use App\Http\Requests\Admin\ProductFilterRequest;
use App\Models\ProductFilter;
use App\Orchid\Core\Actions;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ProductFilterEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Изменить фильтр';

    private ProductFilter $productFilter;

    public function commandBar() : array
    {
        return Actions::make([
            Actions\Save::for($this->productFilter)
        ]);
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(ProductFilter $productFilter): array
    {
        $this->productFilter = $productFilter;

        if ($this->productFilter->exists) {
            $this->name = $this->productFilter->title;
        }

        return [
            'filter' => $this->productFilter,
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
                CheckBox::make('filter.active')
                    ->title(__('admin.active'))
                    ->sendTrueOrFalse()
                    ->horizontal(),

                Input::make('filter.title')
                    ->title(__('admin.title'))
                    ->required()
                    ->horizontal(),

                Input::make('filter.sort')
                    ->title(__('admin.sort'))
                    ->type('number')
                    ->min(0)
                    ->help('Чем меньше значение, тем выше в списке')
                    ->required()
                    ->horizontal()
            ])
        ];
    }

    public function save(ProductFilter $productFilter, ProductFilterRequest $request)
    {
        $data = $request->validated()['filter'];

        $productFilter->update($data);
        Toast::success(__('admin.toasts.updated'));
    }
}
