<?php

namespace App\Orchid\Screens\Content\Story;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Content\Story\StoryEditLayout;
use App\Orchid\Layouts\Content\Story\StoryPageListTableBlockHeader;
use App\Orchid\Layouts\Content\Story\StoryPageListTableLayout;
use Domain\Audience\Models\Audience;
use Domain\Image\Models\Attachment;
use Domain\Story\Models\Story;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;
use App\Orchid\Actions\CreateChild;

class StoryEditScreen extends Screen
{
    public ?string $name = 'Создание / редактирование истории';

    public $description = null;

    public ?Story $story = null;

    public ?bool $exists = false;

    public function query(?int $id = null): array
    {
        $story = !empty($id) ? Story::findOrFail($id) : new Story();

        $story->load('pages');

        $this->exists = $story->exists;
        $this->story = $story;

        return [
            'story' => $story
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        $actions = [
            Actions\Save::for($this->story),
            Actions\Delete::for($this->story),
        ];

        if ($this->exists) {
            $actions[] = CreateChild::for($this->story, 'platform.stories.pages.create')
                ->setTitle('admin.story.page.create');
        }

        return Actions::make($actions);
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            StoryEditLayout::class,
            StoryPageListTableBlockHeader::class,
            StoryPageListTableLayout::class,
        ];
    }

    public function save(Request $request): RedirectResponse
    {
        $id = (int) Arr::get($request->route()->parameters(), 'id', 0);

        $data = $request->only([
            'active',
            'auto_open',
            'title',
            'imageId',
            'audienceId',
            'available_in_groups',
        ]);

        $story = !empty($id) ?  Story::findOrFail($id) : new Story();


        try {
            DB::beginTransaction();

            $audienceId = Arr::pull($data, 'audienceId');
            if ($audienceId) {
                $audience = Audience::findOrFail($audienceId);
                $story->audience_id = $audience->id;
            } else {
                $story->audience_id = null;
            }

            $imageId = Arr::pull($data, 'imageId');

            if ($imageId) {
                $image = Attachment::findOrFail($imageId);
                $story->image()->associate($image);
            } else {
                $story->image()->dissociate();
            }

            $story->fill($data);
            $story->save();

            DB::commit();

            Alert::info('История успешно сохранена');
        } catch (Exception $e) {
            DB::rollBack();

            Log::channel('debug')->error('Ошибка при сохранении истории', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            Alert::error('Произошла ошибка при сохранении истории');
        }

        return redirect()->route('platform.stories.list');
    }

    public function delete(int $id): RedirectResponse
    {
        $story = Story::findOrFail($id);

        $story->delete();

        Alert::info("Удалена запись \"{$story->title}\"");

        return redirect()->route('platform.stories.list');
    }
}
