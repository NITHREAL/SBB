<?php

namespace App\Orchid\Screens\Seo\Page;

use App\Http\Requests\Admin\PageRequest;
use App\Models\Enums\PageListEnum;
use App\Models\Page;
use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Seo\MetaTagValuesLayout;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PageEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить страницу';

    public Page $page;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Page $page): array
    {
        if ($page->exists) {
            $page->load('metaTagValues');
            $this->name = $page->title;
        }

        $this->page = $page;

        return [
            'page' => $page,
        ];
    }

    public function commandBar()
    {
        return Actions::make([
            Actions\Save::for($this->page)
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
            Layout::rows([
                Select::make('page.slug')
                    ->title(__('admin.page.page'))
                    ->options(PageListEnum::toArray())
                    ->horizontal(),
            ])->title(__('admin.page.info')),
            (new MetaTagValuesLayout($this->page->metaTagValues))->title(__('admin.seo'))
        ];
    }

    public function save(Page $page, PageRequest $request)
    {
        $slug = $request->get('page')['slug'];
        $page->fill([
            'slug' => $slug,
            'title' => PageListEnum::$slug()->label
        ])->save();
        $page->setMetaTagValues($request->get('meta_tag_values'));

        Toast::success(__('admin.toasts.updated'));
        return redirect()->route("platform.pages.edit", ['page' => $page]);
    }
}
