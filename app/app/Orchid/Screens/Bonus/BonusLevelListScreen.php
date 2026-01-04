<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Bonus;

use App\Orchid\Layouts\Bonus\BonusLevelListLayout;
use Domain\BonusLevel\Models\BonusLevel;
use Orchid\Screen\Screen;

class BonusLevelListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'bonusLevels' => BonusLevel::filters()->defaultSort('number', 'asc')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('platform.bonus.Bonus levels');
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return __('platform.bonus.A comprehensive list of all bonus levels');
    }

    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            BonusLevelListLayout::class,
        ];
    }
}
