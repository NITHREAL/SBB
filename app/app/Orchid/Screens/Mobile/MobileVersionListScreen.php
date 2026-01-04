<?php

namespace App\Orchid\Screens\Mobile;

use Domain\MobileVersion\Models\MobileVersion;
use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Mobile\MobileVersionListLayout;
use Orchid\Screen\Screen;

class MobileVersionListScreen extends Screen
{
    /**
     * @var string
     */
    public $name = 'Настройка версии мобильного приложения';

    public $description = 'В этом разделе вы можете управлять версиями мобильного приложения и настроить необходимость обновления для пользователей';
    /**
     * @return array
     */
    public function query(): array
    {
        return [
            'mobile_versions' => MobileVersion::filters()
                ->defaultSort('id', 'desc')
                ->paginate()
        ];
    }

    public function commandBar()
    {
        return Actions::make([
            new Actions\Create('platform.mobile-versions.create')
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
            MobileVersionListLayout::class
        ];
    }
}
