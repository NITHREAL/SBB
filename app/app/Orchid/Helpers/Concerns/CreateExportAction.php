<?php

declare(strict_types=1);

namespace App\Orchid\Helpers\Concerns;

use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use App\Orchid\Core\Actions;

trait CreateExportAction
{
    protected function actionsExportTable($type, $filter, $title = null)
    {
        $action = new Actions\ExportTable($type, $filter);
        $action->attr('title', $title);
        return $action;
    }

    public function createExport(Request $request): void
    {
        $type = $request->get('type');
        $filter = $request->get('filter');
        $attr = $request->get('attr');
        $user = auth()->user();

        call_user_func([$type, 'dispatch'], $filter, $user, $attr);

        Alert::info(
            'Формирование отчета началось. Вы получите оповещение по завершению'
        );
    }
}
