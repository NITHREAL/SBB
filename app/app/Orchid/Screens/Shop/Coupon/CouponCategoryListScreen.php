<?php

namespace App\Orchid\Screens\Shop\Coupon;

use App\Orchid\Layouts\Shop\Coupon\CouponCategoryListLayout;
use Domain\CouponCategory\Models\CouponCategory;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class CouponCategoryListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'categories' => CouponCategory::query()
                ->orderBy('sort')
                ->paginate(20),
        ];
    }

    public function name(): string
    {
        return 'Категории купонов';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make(__('admin.create'))
                ->icon('plus')
                ->route('platform.coupons.category.create'),

        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            CouponCategoryListLayout::class,
        ];
    }

}
