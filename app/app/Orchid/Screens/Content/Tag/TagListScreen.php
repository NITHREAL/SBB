<?php

namespace App\Orchid\Screens\Content\Tag;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\Tag\TagListLayout;
use Domain\Tag\Models\Tag;
use Orchid\Screen\Screen;

class TagListScreen extends Screen
{

    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список тегов';

    public function description(): string
    {
        return 'Теги отображаются как плашка на товарах и используются в подборках товаров';
    }

    public function commandBar(): array
    {
        return Actions::make([
            new Actions\Create('platform.tags.create'),
        ]);
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'tags' => Tag::filters()->get()
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
            TagListLayout::class
        ];
    }
}
