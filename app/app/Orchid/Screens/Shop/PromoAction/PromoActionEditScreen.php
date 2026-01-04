<?php

namespace App\Orchid\Screens\Shop\PromoAction;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\PromoAction\PromoActionEditLayout;
use App\Orchid\Layouts\Shop\PromoAction\PromoActionProductsLayout;
use Domain\PromoAction\DTO\PromoActionChangeDTO;
use Domain\PromoAction\Models\PromoAction;
use Domain\PromoAction\Requests\Admin\PromoActionRequest;
use Domain\PromoAction\Services\PromoActionChangeService;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PromoActionEditScreen extends Screen
{
    private ?PromoAction $promoAction = null;

    public function __construct(
        private readonly PromoActionChangeService $promoActionChangeService,
    ) {
    }

    public function name(): string
    {
        $title = $this->promoAction?->title;

        return $title
            ? sprintf('Редактирование промо акции %s', $title)
            : 'Добавление промо акции';
    }

    public function query(?int $id = null): array
    {
        $promoAction = empty($id)
            ? new PromoAction()
            : PromoAction::findOrFail($id);

        $this->promoAction = $promoAction;

        return [
            'promoAction'   => $promoAction,
            'products'      => $promoAction->products,
        ];
    }

    public function commandBar() : array
    {
        $actions = [
            Actions\Save::for($this->promoAction)
        ];

        if ($this->promoAction->id) {
            $actions[] = Actions\Delete::for($this->promoAction);
        }

        return Actions::make($actions);
    }

    public function layout(): array
    {
        $tabs = [
            'Информация' => PromoActionEditLayout::class,
        ];

        if ($this->promoAction->id) {
            $tabs['Товары'] = PromoActionProductsLayout::class;
        }

        return [
            Layout::tabs($tabs),
        ];
    }

    public function save(PromoActionRequest $request, ?int $id = null): RedirectResponse
    {
        $promoActionDTO = PromoActionChangeDTO::make($request->validated());

        if (empty($id)) {
            $promoAction = $this->promoActionChangeService->create($promoActionDTO);
        } else {
            $promoAction = $this->promoActionChangeService->update($id, $promoActionDTO);
        }

        Toast::success('Промо акция успешно сохранена');

        return redirect()->route('platform.promo-actions.edit', $promoAction->id);
    }
}
