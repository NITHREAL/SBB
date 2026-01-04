<?php

namespace App\Orchid\Core;

use App\Orchid\Core\Actions\BaseAction;
use Orchid\Screen\Contracts\Actionable;

class Actions
{
    /**
     * @param BaseAction[] $actions
     *
     * @return Actionable[]
     */
    public static function make(array $actions): array
    {
        return array_map(fn (BaseAction $action) => $action->render(), $actions);
    }
}
