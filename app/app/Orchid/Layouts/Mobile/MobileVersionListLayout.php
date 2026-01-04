<?php

namespace App\Orchid\Layouts\Mobile;

use Domain\MobileVersion\Enums\MobileVersionPlatformEnum;
use Domain\MobileVersion\Enums\MobileVersionStatusEnum;
use App\Orchid\Core\Actions;
use Domain\MobileVersion\Models\MobileVersion;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class MobileVersionListLayout extends Table
{
    /**
     * @var string
     */
    protected $target = 'mobile_versions';

    /**
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('status', __('admin.mobile_version.status'))
                ->render(function ($instance){
                    return MobileVersionStatusEnum::toArray()[$instance->status];
                })
                ->sort(),
            TD::make('platform', __('admin.mobile_version.platform'))
                ->render(function ($instance){
                    return MobileVersionPlatformEnum::toArray()[$instance->platform];
                })
                ->sort(),
            TD::make('version', __('admin.mobile_version.version'))
                ->sort(),
            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (MobileVersion $mobileVersion) {
                    return Link::make(__('Edit'))
                        ->route('platform.mobile-versions.edit', $mobileVersion->id)
                        ->icon('pencil');
                })
        ];
    }
}
