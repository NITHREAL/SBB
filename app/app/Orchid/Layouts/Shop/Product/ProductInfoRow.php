<?php

namespace App\Orchid\Layouts\Shop\Product;

use App\View\Components\Images;
use Domain\Product\Models\Product;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Layouts\Legend;
use Orchid\Screen\Repository;
use Orchid\Screen\Sight;
use Orchid\Support\Blade;
use Orchid\Support\Facades\Layout;

class ProductInfoRow extends Legend
{
    protected $target = 'product';

    protected function columns(): array
    {
        return [
            Sight::make('id', __('admin.id')),
            Sight::make('system_id', __('admin.system_id')),
            Sight::make('active', __('admin.active'))->bool(),
            Sight::make('sort', __('admin.sort')),
            Sight::make('sku', __('admin.product.sku')),
            Sight::make('unit.title', __('admin.product.unit'))
                ->render(function (Product $product) {
                    return !!$product->unit ? $product->unit->title : null;
                }),

            Sight::make('title', __('admin.title')),

            Sight::make('slug', __('admin.slug'))
                ->render(function (Product $product) {
                    return '<input class="form-control" type="text" max="255" placeholder="' .
                        __('admin.slug') .
                        ' " value="' . $product->slug . '" name="product[slug]" />';
                }),

            Sight::make('sort', __('admin.sort'))
                ->render(function (Product $product) {
                    return '<input class="form-control" type="number" min="0" placeholder="' .
                        __('admin.slug') .
                        ' " value="' . $product->sort . '" name="product[sort]" />';
                }),

            Sight::make('description', __('admin.description')),
            Sight::make('composition', __('admin.product.composition')),
            Sight::make('storage_conditions', __('admin.product.storage_conditions')),
            Sight::make('proteins', __('admin.product.proteins')),
            Sight::make('fats', __('admin.product.fats')),
            Sight::make('carbohydrates', __('admin.product.carbohydrates')),
            Sight::make('', __('admin.product.nutrition'))->render(function(Product $product) {
                return "{$product->nutrition_kcal} ккал/{$product->nutrition_kj} кДж";
            }),
            Sight::make('weight', __('admin.product.weight')),
            Sight::make('rating', __('admin.product.rating')),
            Sight::make('shelf_life', __('admin.product.shelf_life')),

            Sight::make('by_preorder', __('admin.product.by_preorder'))->bool(),

            Sight::make('show_as_preorder', __('admin.product.show_as_preorder'))
                ->render(function (Product $product) {
                    return (string)CheckBox::make('product[show_as_preorder]')
                        ->sendTrueOrFalse()
                        ->value($product->show_as_preorder);
                }),

            Sight::make('cooking', __('admin.product.cooking'))->bool(),
            Sight::make('is_ready_to_eat', __('admin.product.is_ready_to_eat'))->bool(),

            Sight::make('created_at', __('admin.created_at'))
                ->render(function (Product $product) {
                    return $product->created_at->format('d-m-Y H:m:s');
                }),
            Sight::make('updated_at', __('admin.updated_at'))
                ->render(function (Product $product) {
                    return $product->updated_at->format('d-m-Y H:m:s');
                }),

            Sight::make('categories', __('admin.categories'))
                ->render(function (Product $product) {
                    $rows = [];

                    foreach ($product->categories as $category) {
                        $rows[] = Link::make($category->title)
                            ->route('platform.categories.show', $category);
                    }


                    return $rows ? Layout::rows($rows)->build(new Repository())->toHtml() : '';
                }),
        ];
    }
}
