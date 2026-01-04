<?php

namespace App\Orchid\Screens\Faq\FaqCategory;

use App\Orchid\Layouts\Faq\FaqCategory\FaqCategoryListLayout;
use Domain\Faq\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class FaqCategoryListScreen extends Screen
{
    public function name(): ?string
    {
        return 'Справочник';
    }

    public function query(): array
    {
        return [
            'faqCategories' => FaqCategory::query()
                ->filters()
                ->orderBy('sort')
                ->paginate(),
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make(__('admin.create'))
                ->icon('plus')
                ->route('platform.faq-categories.create'),
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
            FaqCategoryListLayout::class,
        ];
    }

    public function activate(Request $request): void
    {
        $faqCategoryId = (int) Arr::get($request->route()->parameters(), 'id', 0);

        $activate = (bool)$request->get('activate', false);

        $faqCategory = FaqCategory::findOrFail($faqCategoryId);

        $faqCategory->active = $activate;
        $faqCategory->save();

        Toast::success('Активность успешно изменена');
    }
}
