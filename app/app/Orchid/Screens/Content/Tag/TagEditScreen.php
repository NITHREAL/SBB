<?php

namespace App\Orchid\Screens\Content\Tag;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\Tag\TagEditLayout;
use Domain\Tag\Models\Tag;
use Domain\Tag\Requests\Admin\TagRequest;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class TagEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string|null
     */
    public ?string $name = 'Добавить тег';

    public ?bool $exists = false;

    public ?Tag $tag = null;


    /**
     * Query data.
     *
     * @return array
     */
    public function query(Tag $tag): array
    {
        $this->exists = $tag->exists;

        if ($this->exists) {
            $this->name = $tag->text;
        }

        $this->tag = $tag;

        return [
            'tag' => $tag
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return Actions::make([
            Actions\Save::for($this->tag),
            Actions\Delete::for($this->tag)
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
            TagEditLayout::class
        ];
    }

    public function save(Tag $tag, TagRequest $request)
    {
        $this->exists = $tag->exists;

        $data = $request->validated();


        $tag->fill($data)->save();

        if ($this->exists) {
            Alert::success('Изменения сохранены');
        } else {
            Alert::success("Добавлена запись \"{$tag->text}\"");
        }

        return redirect()->route('platform.tags.list');
    }
}
