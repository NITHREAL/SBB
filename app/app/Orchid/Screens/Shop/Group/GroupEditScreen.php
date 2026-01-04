<?php

namespace App\Orchid\Screens\Shop\Group;

use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\Group\GroupEditLayout;
use App\Orchid\Layouts\Shop\Group\GroupProductsLayout;
use App\Orchid\Layouts\Shop\Group\GroupTagsLayout;
use Domain\ProductGroup\DTO\ProductGroupCreateDTO;
use Domain\ProductGroup\Models\ProductGroup;
use Domain\ProductGroup\Requests\Admin\GroupRequest;
use Domain\ProductGroup\Services\ProductGroupModifierService;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class GroupEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Добавить подборку';

    private ProductGroup $group;

    public function __construct(
        protected ProductGroupModifierService $groupService,
    ) {
    }

    public function commandBar() : array
    {
        return Actions::make([
            Actions\Save::for($this->group),
            Actions\Delete::for($this->group)
        ]);
    }

    /**
     * Query data.
     *
     * @param ProductGroup $group
     * @return array
     */
    public function query(ProductGroup $group): array
    {
        $this->group = $group;

        if ($group->exists) {
            $group->load('products');
            $group->load('tags');

            $this->name = $group->title;
        }

        return [
            'group'     => $group,
            'products'  => $group->products,
            'tags'      => $group->tags,
        ];
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        $tabs = [
            __('admin.group.info') => GroupEditLayout::class
        ];

        if ($this->group->exists) {
            $tabs['Теги'] = GroupTagsLayout::class;
            $tabs[__('admin.group.products')] = GroupProductsLayout::class;
        }

        return [
            Layout::tabs($tabs)
        ];
    }

    public function save(ProductGroup $group, GroupRequest $request): RedirectResponse
    {
        $groupDTO = ProductGroupCreateDTO::make($request->validated());

        if ($group->id) {
            $this->groupService->updateGroup($group->id, $groupDTO);
        } else {
            $this->groupService->createGroup($groupDTO);
        }

        Toast::success(__('admin.toasts.updated'));

        return redirect()->route('platform.groups.list');
    }

    public function delete(ProductGroup $group): RedirectResponse
    {
        $this->groupService->delete($group->id);

        return redirect()->route('platform.groups.list');
    }
}
