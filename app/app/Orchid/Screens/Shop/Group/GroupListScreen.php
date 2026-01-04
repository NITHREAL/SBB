<?php

namespace App\Orchid\Screens\Shop\Group;

use App\Orchid\Layouts\Shop\Group\GroupFiltersLayout;
use App\Orchid\Layouts\Shop\Group\GroupListLayout;
use Domain\ProductGroup\Models\ProductGroup;
use Domain\ProductGroup\Services\ProductGroupModifierService;
use Exception;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class GroupListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public ?string $name = 'Список подборок';

    public function __construct(
        private readonly ProductGroupModifierService $groupModifierService,
    ) {
    }

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'groups' => ProductGroup::filtersApplySelection(GroupFiltersLayout::class)
                ->filters()
                ->withCount('products')
                ->defaultSort('sort')
                ->paginate()
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make(__('admin.create'))
                ->icon('plus')
                ->route('platform.groups.create'),
        ];
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            GroupFiltersLayout::class,
            GroupListLayout::class
        ];
    }

    /**
     * @throws Exception
     */
    public function activate(Request $request): void
    {
        $id = $request->get('id');
        $activate = $request->get('activate', false);

        $this->changeActivityColumn($id, $activate, 'active');
    }

    /**
     * @throws Exception
     */
    public function mobile(Request $request): void
    {
        $id = $request->get('id');
        $activate = $request->get('activate', false);

        $this->changeActivityColumn($id, $activate, 'mobile');
    }

    /**
     * @throws Exception
     */
    public function site(Request $request): void
    {
        $id = $request->get('id');
        $activate = $request->get('activate', false);

        $this->changeActivityColumn($id, $activate, 'site');
    }

    /**
     * @throws Exception
     */
    private function changeActivityColumn(
        int $groupId,
        bool $value,
        string $column,
    ): void {
        $productGroup = ProductGroup::findOrFail($groupId);

        $this->groupModifierService->changeAvailabilityColumn(
            $productGroup,
            $column,
            $value,
        );

        Toast::success('Активность успешно изменена');
    }
}
