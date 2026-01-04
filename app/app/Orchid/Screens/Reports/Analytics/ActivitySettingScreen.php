<?php

namespace App\Orchid\Screens\Reports\Analytics;

use App\Http\Requests\ReportAnalyticsActivityRequest;
use App\Orchid\Layouts\Reports\Analytics\ActivitySettingLayout;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Color;

class ActivitySettingScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name;

    public function __construct()
    {
        $this->name =  'Сформировать "' . __('admin.analytic.activity') . '"';
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [];
    }

    public function create(ReportAnalyticsActivityRequest $request): RedirectResponse
    {
        $filter = $request->all();
        $parametersData = [
            'created_at' => [
                'start' => $filter['created_at']['start'] ?? null,
                'end' => $filter['created_at']['end'] ?? null
            ],
        ];

        return redirect()->route('platform.analytic.activity.list', $parametersData);
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::block(ActivitySettingLayout::class)
                ->title('Настройки "' . __('admin.analytic.activity') . '"')
                ->commands(
                    Button::make("Сформировать")
                        ->type(Color::DEFAULT())
                        ->icon('check')
                        ->method('create')
                )
        ];
    }
}
