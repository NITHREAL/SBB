<?php

namespace App\Orchid\Screens\Reports\Analytics;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\Concerns\CreateExportAction;
use App\Orchid\Layouts\Reports\Analytics\UploadUserFilterLayout;
use App\Orchid\Layouts\Reports\Analytics\UploadUserListLayout;
use Domain\User\Jobs\Analytics\ExportAnalyticJob;
use Domain\User\Models\User;
use Domain\User\Services\UserAnalyticsService;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

/** Отчет выгрузка клиентов */
class UploadUserListScreen extends Screen
{
    use CreateExportAction;

    private string $fileName = 'Отчет_выгрузка_клиентов.xlsx';

    /**
     * Display header name.
     *
     * @var string
     */
    public string $name;

    public function __construct(
        private readonly UserAnalyticsService $userAnalyticsService,
    ) {
        $this->name = __('admin.analytic.unload');
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $paginate = User::query()
            ->selectRaw('users.id')
            ->groupBy('users.id')
            ->filtersApplySelection(UploadUserFilterLayout::class)
            ->filters()
            ->defaultSort('id', 'asc')
            ->paginate();

        $preparedUsers = $this->userAnalyticsService->getUsersData(
            $paginate->pluck('id')->toArray()
        );

        $paginate->setCollection($preparedUsers);

        return [
            'analytic_unload' => $paginate
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        $filter = request()->all();

        return Actions::make([
            $this->actionsExportTable(ExportAnalyticJob::class, $filter, $this->name),
        ]);
    }

    public function downloadExcel(): void
    {
        $filePath = storage_path('app/' . $this->fileName);

        if (file_exists($filePath)) {
            Alert::info(
                'Создание файла xlsx завершено_ <a href="' . config('app.url')
                . '/download-report-upload-user" target="_blank"> нажмите чтобы его скачать</a>.'
            );
        } else {
            Alert:: error('Файл xlsx не найден');
        }
        Alert::info(
            'Формирование отчета началось. Вы получите оповещение по завершению'
        );
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            UploadUserFilterLayout::class,
            UploadUserListLayout::class
        ];
    }
}
