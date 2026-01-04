<?php

namespace App\Orchid\Screens\Shop\Lottery;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\Lottery\LotteryEditLayout;
use App\Orchid\Layouts\Shop\Lottery\LotteryProductsLayout;
use Domain\Lottery\DTO\LotteryChangeDTO;
use Domain\Lottery\Models\Lottery;
use Domain\Lottery\Requests\Admin\LotteryChangeRequest;
use Domain\Lottery\Services\LotteryChangeService;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class LotteryEditScreen extends Screen
{
    private ?Lottery $lottery = null;

    public function __construct(
        private readonly LotteryChangeService $lotteryChangeService,
    ) {
    }

    public function name(): string
    {
        $title = $this->lottery?->title;

        return $title
            ? sprintf('Редактирование розыгрыша %s', $title)
            : 'Добавление розыгрыша';
    }

    public function query(?int $id = null): array
    {
        $lottery = empty($id)
            ? new Lottery()
            : Lottery::findOrFail($id);

        $this->lottery = $lottery;

        return [
            'lottery'   => $lottery,
            'products'  => $lottery->products,
        ];
    }

    public function commandBar() : array
    {
        $actions = [
            Actions\Save::for($this->lottery),
        ];

        if ($this->lottery->id) {
            $actions[] = Actions\Delete::for($this->lottery);
        }

        return Actions::make($actions);
    }

    public function layout(): array
    {
        $tabs = [
            'Информация' => LotteryEditLayout::class,
        ];

        if ($this->lottery->id) {
            $tabs['Товары'] = LotteryProductsLayout::class;
        }

        return [
            Layout::tabs($tabs),
        ];
    }

    public function save(LotteryChangeRequest $request, ?int $id = null): RedirectResponse
    {
        $lotteryDTO = LotteryChangeDTO::make($request->validated());

        if (empty($id)) {
            $lottery = $this->lotteryChangeService->create($lotteryDTO);
        } else {
            $lottery = $this->lotteryChangeService->update($id, $lotteryDTO);
        }

        Toast::success('Розыгрыш успешно сохранен');

        return redirect()->route('platform.lotteries.edit', $lottery->id);
    }
}
