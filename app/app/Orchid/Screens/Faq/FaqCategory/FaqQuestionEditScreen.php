<?php

namespace App\Orchid\Screens\Faq\FaqCategory;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Faq\Faq\FaqQuestionEditLayout;
use Domain\Faq\DTO\FaqDTO;
use Domain\Faq\Models\Faq;
use Domain\Faq\Models\FaqCategory;
use Domain\Faq\Requests\Admin\FaqRequest;
use Domain\Faq\Services\Faq\FaqModifyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class FaqQuestionEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Добавить вопрос';

    private ?Faq $question = null;

    private ?FaqCategory $faqCategory = null;

    public function __construct(
        private readonly FaqModifyService $faqModifyService,
    ) {
    }

    public function query(int $parent_id, ?int $id = null): array
    {
        $this->question = $id ? Faq::findOrFail($id) : new Faq();
        $this->faqCategory = FaqCategory::findOrFail($parent_id);

        if ($this->question->exists) {
            $this->name = $this->question->title;
        }

        return [
            'question'      => $this->question,
            'faqCategory'   => $this->faqCategory,
        ];
    }

    public function commandBar() : array
    {
        $actions = [
            Actions\Save::for($this->question),
        ];

        if ($this->question->exists) {
            $actions[] = Actions\Delete::for($this->question);
        }

        return Actions::make($actions);
    }

    public function layout(): array
    {
        return [
            'Иформация о вопросе' => FaqQuestionEditLayout::class,
        ];
    }

    public function save(int $parent_id, FaqRequest $request): RedirectResponse
    {
        $faqId = (int) Arr::get($request->route()->parameters(), 'id', 0);

        $faqDTO = FaqDTO::make($request->validated(), $parent_id, $faqId);

        if ($faqId) {
            $this->faqModifyService->updateFaq($faqId, $faqDTO);
        } else {
            $this->faqModifyService->createFaq($faqDTO);
        }

        Toast::success(__('admin.toasts.updated'));

        return redirect()->route('platform.faq-categories.edit', ['category' => $parent_id]);
    }

    public function delete(int $parent_id, int $id): RedirectResponse
    {
        $this->faqModifyService->deleteFaq($id);

        Toast::success('Категория вопросов успешно удалена');

        return redirect()->route('platform.faq-categories.edit', ['category' => $parent_id]);
    }
}
