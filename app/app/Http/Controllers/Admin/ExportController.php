<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Infrastructure\Export\ExportManager;
use Infrastructure\Http\Requests\Admin\ExportRequest;

class ExportController extends Controller
{
    public function export(ExportRequest $request)
    {
        $filter = $request->get('filter', []);

        if ($request->has('model')) {
            $filter['model'] = $request->get('model');
        }

        return ExportManager::exportByType($request->get('type'), $filter ?? []);
    }
}
