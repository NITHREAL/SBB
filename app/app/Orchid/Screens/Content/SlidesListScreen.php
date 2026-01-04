<?php

namespace App\Orchid\Screens\Content;

use App\Models\Scopes\ActiveScope;
use App\Models\Slide;
use App\Orchid\Layouts\Content\SlidesFiltersLayout;
use App\Orchid\Layouts\Content\SlidesListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class SlidesListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Баннеры';
    public string $route = 'sliders';
    public string $sliderType = 'main';

    public function commandBar(): array
    {
        return [
            Link::make(__('admin.create'))
                ->icon('plus')
                ->route("platform.{$this->route}.create")
        ];
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'slides' => Slide::with('cities')
                ->withoutGlobalScope(ActiveScope::class)
                ->whereHas('slider', function ($query) {
                    return $query
                        ->withoutGlobalScope(ActiveScope::class)
                        ->where('type', $this->sliderType);
                })
                ->filters()
                ->filtersApplySelection(SlidesFiltersLayout::class)
                ->defaultSort('id', 'desc')
                ->paginate()
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
            SlidesFiltersLayout::class,
            new SlidesListLayout($this->route)
        ];
    }

    public function activate(Request $request)
    {
        Slide::findOrFail($request->get('id'))->activate($request->get('activate'));
    }
}
