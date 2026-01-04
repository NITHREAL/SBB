<?php

namespace App\Orchid\Screens\References\LegalEntity;

use App\Http\Requests\Admin\LegalEntityRequest;
use App\Models\Enums\OrderStatusEnum;
use App\Models\LegalEntity;
use App\Orchid\Core\Actions;
use App\Orchid\Layouts\References\LegalEntity\LegalEntityEditLayout;
use Illuminate\Http\RedirectResponse;
use Orchid\Platform\Components\Notification;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class LegalEntityEditScreen extends Screen
{
    public $name = 'Добавить юридическое лицо';

    private LegalEntity $entity;

    public function query(LegalEntity $entity): array
    {
        $this->entity = $entity;

        if ($entity->exists) {
            $this->name = $entity->title;
        }

        return [
            'legal_entity' => $entity
        ];
    }

    public function commandBar(): array
    {
        return Actions::make([
            Actions\Save::for($this->entity),
            Actions\Delete::for($this->entity)
        ]);
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            LegalEntityEditLayout::class
        ];
    }

    public function save(LegalEntity $entity, LegalEntityRequest $request): RedirectResponse
    {
        $data = $request->validated()['legal_entity'];

        $entity->fill($data)->save();

        Alert::success(
            __('admin.toasts.updated')
        );

        return redirect()->route('platform.legal_entities.edit', $entity);
    }
}
