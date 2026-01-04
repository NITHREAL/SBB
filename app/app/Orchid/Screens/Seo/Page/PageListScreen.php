<?php

namespace App\Orchid\Screens\Seo\Page;

use App\Models\Page;
use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Content\Page\PageListLayout;
use Orchid\Screen\Screen;

class PageListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список страниц';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'pages' => Page::filters()
                ->defaultSort('id', 'desc')
                ->paginate()
        ];
    }

    public function commandBar()
    {
        return Actions::make([
            new Actions\Create('platform.pages.create')
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
            PageListLayout::class
        ];
    }
}
