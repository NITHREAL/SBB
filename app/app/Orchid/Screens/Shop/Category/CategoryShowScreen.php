<?php

namespace App\Orchid\Screens\Shop\Category;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\Category\CategoryInfoRow;
use Domain\Image\Models\Attachment;
use Domain\Product\Models\Category;
use Domain\Product\Requests\Admin\Category\CategoryRequest;
use Domain\Product\Services\Category\Image\CategoryImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CategoryShowScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string|null
     */
    public ?string $name = 'Добавить категорию';

    public ?Category $category = null;

    /**
     * Query data.
     *
     * @param Category $category
     * @return array
     */
    public function query(Category $category): array
    {
        if ($category->exists) {
            $category->load('metaTagValues');
            $this->name = $category->title;
        }
        $this->category = $category;

        return [
            'category' => $category
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
            Actions\Save::for($this->category)
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
            Layout::tabs([
                __('admin.category.info') => CategoryInfoRow::class
            ])
        ];
    }

    public function save(
        Category $category,
        CategoryRequest $request,
        CategoryImageService $categoryImageService
    ): RedirectResponse {
        $data = $request->get('category');
        $category->update($data);

        if ($request->has('attachment')) {
            $attachmentId = Arr::first($request->get('attachment'));
            $attachment = Attachment::find($attachmentId);
            $categoryImageService->attachImageToCategory($attachment, $category);
        }

        Toast::success(__('admin.toasts.category.updated', ['category' => $category->title]));

        return redirect()->route('platform.categories.list');
    }
}
