<?php

namespace App\Orchid\Screens\Shop\Coupon;

use Domain\CouponCategory\DTO\CouponCategoryDTO;
use Domain\CouponCategory\Models\CouponCategory;
use App\Orchid\Core\Actions;
use App\Orchid\Layouts\Shop\Coupon\CouponCategoryEditLayout;
use Domain\CouponCategory\Services\CouponCategory\CouponCategoryChangeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Orchid\Screen\Screen;
use App\Orchid\Core\Actions\Save;
use App\Orchid\Core\Actions\Delete;
use Orchid\Support\Facades\Alert;

class CouponCategoryEditScreen extends Screen
{
    public $description = null;

    public ?CouponCategory $category = null;

    public ?bool $exists = false;

    public function __construct(
        protected CouponCategoryChangeService $couponCategoryService,
    ) {
    }

    public function name(): string
    {
        return $this->category?->title
            ? sprintf('Редактирование категории купонов %s', $this->category->title)
            : 'Добавление категории купонов';
    }

    /**
     * Query data.
     *
     * @param int $id
     * @return array
     */
    public function query(?int $id = null): array
    {
        $category = !empty($id) ? CouponCategory::findOrFail($id) : new CouponCategory();

        $this->exists = $category->exists;
        $this->category = $category;

        return [
            'category' => $category,
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return Actions::make([
            Save::for($this->category),
            Delete::for($this->category),
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
            CouponCategoryEditLayout::class,
        ];
    }

    public function save(Request $request): RedirectResponse
    {
        $couponId = (int) Arr::get($request->route()->parameters(), 'id', 0);

        $couponCategory = !empty($couponId) ? CouponCategory::findOrFail($couponId) : new CouponCategory();

        $couponCategoryDTO = CouponCategoryDTO::make($request->all());

        if ($couponCategory->id) {
            $this->couponCategoryService->update($couponCategory->id, $couponCategoryDTO);
        } else {
            $this->couponCategoryService->create($couponCategoryDTO);
        }

        Alert::info('Категория успешно сохранена');

        return redirect()->route('platform.coupons.category.list');
    }

    public function delete(int $id): RedirectResponse
    {
        $couponCategory = CouponCategory::findOrFail($id);

        $this->couponCategoryService->delete($couponCategory->id);

        Alert::info("Удалена категория купонов \"{$couponCategory->name}\"");

        return redirect()->route('platform.coupons.category.list');
    }
}
