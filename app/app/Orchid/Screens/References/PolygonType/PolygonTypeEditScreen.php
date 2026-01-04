<?php

namespace App\Orchid\Screens\References\PolygonType;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\References\PolygonType\PolygonTypeEditLayout;
use Domain\Order\Models\Delivery\PolygonType;
use Domain\Order\Requests\Admin\Delivery\PolygonTypeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class PolygonTypeEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = '';

    protected PolygonType $polygonType;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(int $polygon): array
    {
        $polygonType = PolygonType::findOrFail($polygon);

        $this->name = $polygonType->title;
        $this->polygonType = $polygonType;

        return [
            'polygon_type' => $polygonType
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
            Actions\Save::for($this->polygonType)
        ]);
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            PolygonTypeEditLayout::class,
        ];
    }

    public function save(PolygonTypeRequest $request): RedirectResponse
    {
        $polygonTypeId = (int) Arr::get($request->route()->parameters(), 'polygon', 0);

        $polygonType = !empty($polygonTypeId) ? PolygonType::findOrFail($polygonTypeId) : new PolygonType();

        $data = $request->validated();

        $polygonType->fill($data['polygon_type']);

        $polygonType->save();

        if ($polygonType->exists) {
            Alert::success('Изменния сохранены');
        }

        return redirect()->route('platform.polygon_types.edit', $polygonType->id);
    }
}
