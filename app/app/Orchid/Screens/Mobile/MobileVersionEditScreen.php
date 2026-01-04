<?php

namespace App\Orchid\Screens\Mobile;

use App\Orchid\Core\Actions;
use Domain\MobileVersion\Enums\MobileVersionPlatformEnum;
use Domain\MobileVersion\Enums\MobileVersionStatusEnum;
use Domain\MobileVersion\Models\MobileVersion;
use Domain\MobileVersion\Requests\Admin\MobileVersionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class MobileVersionEditScreen extends Screen
{
    public $name = 'Добавить мобильную версию';

    public function description(): string
    {
        return view('components.mobile_version_description')->render();
    }

    public ?MobileVersion $mobileVersion = null;

    public function query(?int $mobileVersion = null): array
    {
        $this->mobileVersion = !empty($mobileVersion)
            ? MobileVersion::findOrFail($mobileVersion)
            : new MobileVersion();

        return [
            'mobile_version' => $mobileVersion,
        ];
    }

    public function commandBar()
    {
        $actions = [];
        $actions[] = Actions\Save::for($this->mobileVersion);

        if ($this->mobileVersion->exists) {
            $actions[] = Actions\Delete::for($this->mobileVersion);
        }

        return Actions::make($actions);
    }

    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('mobile_version.id')
                    ->hidden(),
                Select::make('mobile_version.status')
                    ->options(MobileVersionStatusEnum::toArray())
                    ->horizontal()
                    ->value($this->mobileVersion?->status)
                    ->title(__('admin.mobile_version.status'))
                    ->help('Выберите статус'),
                Select::make('mobile_version.platform')
                    ->options(MobileVersionPlatformEnum::toArray())
                    ->horizontal()
                    ->value($this->mobileVersion?->platform)
                    ->title(__('admin.mobile_version.platform'))
                    ->help('Выберите платформу'),
                Input::make('mobile_version.version')
                    ->type('text')
                    ->max(255)
                    ->title(__('admin.mobile_version.version'))
                    ->horizontal()
                    ->value($this->mobileVersion?->version)
                    ->placeholder(__('admin.mobile_version.version')),
            ])->title(__('admin.mobile_version.info')),
        ];
    }

    public function save(MobileVersionRequest $request): RedirectResponse
    {
        $versionId = (int) Arr::get($request->route()->parameters(), 'mobileVersion', 0);

        $data = $request->get('mobile_version');

        $mobileVersion = $versionId ? MobileVersion::findOrFail($versionId) : new MobileVersion();

        $mobileVersion->fill($data)->save();

        Toast::success(__('admin.toasts.updated'));
        return redirect()->route("platform.mobile-versions.list");
    }

    public function delete(MobileVersion $mobileVersion)
    {
        $mobileVersion->deleteOrFail();
        Toast::success(__('admin.toasts.softDeleted'));
        return redirect()->route("platform.mobile-versions.list");
    }
}
