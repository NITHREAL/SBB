<?php

namespace App\Orchid\Macros\Cell;

use App\Orchid\Core\Actions\BaseAction;
use App\Orchid\Helpers\Actions\ActionInterface;
use App\Orchid\Macros\MacroInterface;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Contracts\Actionable;
use Orchid\Screen\TD;

class ActionMacro implements MacroInterface
{
    public static function macro(): \Closure
    {
        /** @var BaseAction[]|Actionable[] $actions */
        $closure = function (array $actions) {
            $this->title = __('admin.actions');
            $this->align = TD::ALIGN_CENTER;
            $this->width = 100;

            $this->render(
                function (Model $model) use ($actions) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list(
                            array_map(function ($action) use ($model) {
                                if ($action instanceof BaseAction) {
                                    $action = $action
                                        ->setModel($model)
                                        ->render();
                                }

                                return $action;
                            }, $actions)
                        );
                }
            );

            return $this;
        };

        return $closure;
    }
}
