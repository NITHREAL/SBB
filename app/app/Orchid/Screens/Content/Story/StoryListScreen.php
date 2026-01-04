<?php

namespace App\Orchid\Screens\Content\Story;

use App\Orchid\Layouts\Content\Story\StoryFiltersLayout;
use App\Orchid\Layouts\Content\Story\StoryListLayout;
use Domain\Story\Models\Story;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class StoryListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string|null
     */
    public ?string $name = 'Список историй';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'stories' => Story::filtersApplySelection(StoryFiltersLayout::class)
                ->filters()
                ->paginate(10)
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
            Link::make(__('admin.create'))
                ->icon('plus')
                ->route('platform.stories.create'),
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
            StoryFiltersLayout::class,
            StoryListLayout::class
        ];
    }

    public function activate(Request $request): void
    {
        $storyId = $request->get('id');
        $activate = (bool)$request->get('activate', false);

        $story = Story::findOrFail($storyId);
        $story->active = $activate;
        $story->save();

        Toast::success('Активность успешно изменена');
    }
}
