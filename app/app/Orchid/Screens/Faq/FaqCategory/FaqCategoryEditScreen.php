<?php

namespace App\Orchid\Screens\Faq\FaqCategory;

use App\Orchid\Actions\CreateChild;
use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Faq\Faq\FaqQuestionListLayout;
use App\Orchid\Layouts\Faq\Faq\FaqQuestionListTableHeader;
use App\Orchid\Layouts\Faq\FaqCategory\FaqCategoryEditLayout;
use Domain\Faq\DTO\FaqCategoryDTO;
use Domain\Faq\Models\FaqCategory;
use Domain\Faq\Requests\Admin\FaqCategoryRequest;
use Domain\Faq\Services\FaqCategory\FaqCategoryModifyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class FaqCategoryEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string|null
     */
    public ?string $name = 'Добавить категорию вопросов';

    private ?FaqCategory $category = null;

    public function __construct(
        private readonly FaqCategoryModifyService $faqCategoryModifyService,
    ) {
    }

    public function query(int $category = null): array
    {
        $this->category = !empty($category)
            ? FaqCategory::findOrFail($category)
            : new FaqCategory();

        if ($this->category->exists) {
            $this->category->load(['questions' => function($query) {
                $query->filters();
            }]);

            $this->name = $this->category->title;
        }

        return [
            'category'  => $this->category,
            'questions' => $this->category->exists
                ? $this->category->questions()->filters()->get()
                : collect(),
        ];
    }

    public function commandBar() : array
    {
        $actions = [
            Actions\Save::for($this->category),
        ];

        if (!$this->category->protected) {
            $actions[] = Actions\Delete::for($this->category);
        }

        if ($this->category->exists) {
            $actions[] = CreateChild::for($this->category, 'platform.faq-categories.questions.create')
                ->setTitle('Добавить вопрос');
        }

        return Actions::make($actions);
    }

    public function layout(): array
    {
        $layouts = [
            'Иформация о категории' => FaqCategoryEditLayout::class,
        ];

        if ($this->category->exists) {
            $layouts = array_merge(
                $layouts,
                [
                    FaqQuestionListTableHeader::class,
                    'Вопросы категории'     => FaqQuestionListLayout::class,
                ]
            );
        }

        return $layouts;
    }

    public function save(FaqCategoryRequest $request): RedirectResponse
    {
        $faqCategoryId = (int) Arr::get($request->route()->parameters(), 'category', 0);

        $faqCategoryDTO = FaqCategoryDTO::make($request->validated());

        if ($faqCategoryId) {
            $this->faqCategoryModifyService->updateFaqCategory($faqCategoryId, $faqCategoryDTO);
        } else {
            $this->faqCategoryModifyService->createFaqCategory($faqCategoryDTO);
        }

        Toast::success(__('admin.toasts.updated'));

        return redirect()->route('platform.faq-categories.list');
    }

    public function delete(int $category): RedirectResponse
    {
        $this->faqCategoryModifyService->deleteFaqCategory($category);

        Toast::success('Категория вопросов успешно удалена');

        return redirect()->route('platform.faq-categories.list');
    }
}
