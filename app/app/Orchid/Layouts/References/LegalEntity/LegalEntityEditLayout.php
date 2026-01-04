<?php

namespace App\Orchid\Layouts\References\LegalEntity;

use App\Models\LegalEntity;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Rows;

class LegalEntityEditLayout extends Rows
{
    protected function fields(): array
    {
        /** @var LegalEntity $entity */
        $entity = $this->query->get('legal_entity');

        return [
            Label::make('legal_entity.system_id')
                ->title(__('admin.system_id'))
                ->canSee($entity->exists),

            Input::make('legal_entity.title')
                ->title(__('admin.legal_entity.title'))
                ->required()
                ->horizontal(),

            Input::make('legal_entity.short_title')
                ->title(__('admin.legal_entity.short_title'))
                ->horizontal(),

            Input::make('legal_entity.full_title')
                ->title(__('admin.legal_entity.full_title'))
                ->horizontal(),

            Input::make('legal_entity.last_name')
                ->title(__('admin.legal_entity.last_name'))
                ->horizontal(),

            Input::make('legal_entity.first_name')
                ->title(__('admin.legal_entity.first_name'))
                ->horizontal(),

            Input::make('legal_entity.second_name')
                ->title(__('admin.legal_entity.second_name'))
                ->horizontal(),

            Input::make('legal_entity.certificate')
                ->title(__('admin.legal_entity.certificate'))
                ->horizontal(),

            DateTimer::make('legal_entity.certificate_date')
                ->title(__('admin.legal_entity.certificate_date'))
                ->format('Y-m-d')
                ->horizontal(),

            Input::make('legal_entity.inn')
                ->title(__('admin.legal_entity.inn'))
                ->horizontal(),

            Input::make('legal_entity.ogrn')
                ->title(__('admin.legal_entity.ogrn'))
                ->horizontal(),

            Input::make('legal_entity.okato')
                ->title(__('admin.legal_entity.okato'))
                ->horizontal(),

            Input::make('legal_entity.okpo')
                ->title(__('admin.legal_entity.okpo'))
                ->horizontal(),

            Input::make('legal_entity.sber_login')
                ->title(__('admin.legal_entity.sber_login'))
                ->required()
                ->horizontal(),

            Password::make('legal_entity.sber_password')
                ->title(__('admin.legal_entity.sber_password'))
                ->help($entity->sber_login ? __('admin.legal_entity.pass_help') : '')
                ->required(!$entity->sber_login)
                ->maxlength(255)
                ->horizontal(),

            Password::make('legal_entity.hash_key')
                ->title(__('admin.legal_entity.hash_key'))
                ->help($entity->sber_login ? __('admin.legal_entity.pass_help') : '')
                ->required(!$entity->sber_login)
                ->maxlength(255)
                ->horizontal(),
        ];
    }
}
