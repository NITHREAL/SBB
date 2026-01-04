<?php

namespace App\Orchid\Screens\Reports;

use Domain\Audience\Export\AudienceUsersExport;
use Domain\Audience\Import\AudienceUsersImport;
use Domain\Audience\Jobs\AudienceRecalculateJob;
use Domain\Audience\Models\Audience;
use Domain\Audience\Service\AudienceUsersService;
use Domain\User\Models\User;
use Exception;
use Illuminate\Support\Arr;
use App\Orchid\Core\Actions;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Layout;
use App\Orchid\Helpers\Concerns\CreateExportAction;

class AudienceEditScreen extends Screen
{
    use CreateExportAction;

    public $name = 'Создание / редактирование аудитории';

    public $description = null;

    public ?Audience $audience = null;

    public ?bool $exists = false;

    public function __construct(
        protected AudienceUsersService $audienceUsersService,
    ) {
    }

    public function query(?int $audience = null): array
    {
        $audience = !empty($audience) ? Audience::findOrFail($audience) : new Audience();

        $this->exists = $audience->exists;
        $this->audience = $audience;
        return [
            'audience' => $audience,
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        if ($this->exists) {
            return Actions::make([
                Actions\Save::for($this->audience),
                Actions\Delete::for($this->audience),
                (new Actions\Export('audience_users'))->setTitle('Пример файла с пользователями'),
                $this->actionsExportTable(
                    AudienceUsersExport::class,
                    ['id' => $this->audience->id],
                    __('admin.audience.users_export', ['name' => $this->audience->title]),
                ),
            ]);
        }
        return Actions::make([
            Actions\Save::for($this->audience),
        ]);
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            Layout::columns([
                Layout::rows([
                    Input::make('audience.title')
                        ->title('Название')
                        ->required(true)
                        ->placeholder('Название аудитории'),
                ]),
            ]),
            Layout::columns([
                Layout::rows([
                    Group::make([
                        Input::make('audience.file_csv')
                            ->title('Файл CSV')
                            ->type('file'),
                        Button::make('Загрузить')
                            ->method('importFileCsv')
                            ->type(Color::DEFAULT())
                    ])->alignEnd(),

                    Group::make([
                        Relation::make('audience.users')
                            ->fromModel(User::class, 'first_name', 'id')
                            ->displayAppend('name_with_phone')
                            ->title('Выбор пользователей')
                            ->multiple(),
                        Button::make('Добавить')
                            ->method('importUsers')
                            ->type(Color::DEFAULT())
                    ])->alignEnd(),
                ])
            ]),
        ];
    }

    public function save(Audience $audience, Request $request): RedirectResponse
    {
        $data = Arr::get($request->all(), 'audience');

        $audience->fill($data)->save();

        Alert::info('Аудитория успешно сохранена');

        return redirect()->route('platform.audiences.list');
    }

    public function actionUpdate(Audience $audience, Request $request): void
    {
        $audience->fill($request->get('audience'))->save();

        Alert::info('Обновление списка аудитории..');
    }

    /**
     * @throws Exception
     */
    public function importFileCsv(Audience $audience, Request $request): void
    {
        $file = Arr::get($request->allFiles(), 'audience.file_csv');

        if ($file) {
            try {
                Excel::import(new AudienceUsersImport($audience), $file);

                Alert::info('Импорт пользователей успешно выполнен');
            } catch (Exception $exception) {
                Alert::warning('Во время импорта произошла ошибка');
            }
        } else {
            Alert::warning('Файл не передан');
        }
    }

    public function delete(Audience $audience): RedirectResponse
    {
        $audience->delete();

        Alert::info("Удалена запись \"{$audience->title}\"");

        return redirect()->route('platform.audiences.list');
    }

    public function importUsers(Audience $audience, Request $request): void
    {
        $users = Arr::get($request->get('audience'), 'users', []);

        if (count($users) > 0) {
            $usersCount = $this->audienceUsersService->updateUsersFromSelect($audience, $users);

            Alert::info('Пользователи добавлены: ' . $usersCount);
        } else {
            Alert::warning('Пользователи не переданы');
        }
    }
}
