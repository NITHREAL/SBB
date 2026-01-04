<?php

namespace App\Orchid\Layouts\Shop\Category;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class CategoryInfoRow extends Rows
{
    protected function fields(): array
    {
        $category = $this->query->get('category');

        return [
            Label::make('id')
                ->title(__('admin.id'))
                ->value($category->id)
                ->horizontal(),

            Label::make('system_id')
                ->title(__('admin.system_id'))
                ->value($category->system_id)
                ->horizontal(),

            Label::make('active')
                ->title(__('admin.active'))
                ->value($category->active ? __('admin.activated') : __('admin.deactivate'))
                ->horizontal(),

            Label::make('title')
                ->title(__('admin.title'))
                ->value($category->title)
                ->horizontal(),

            Input::make('category.slug')
                ->title(__('admin.slug'))
                ->value($category->slug)
                ->horizontal(),

            Input::make('category.sort')
                ->title(__('admin.sort'))
                ->value($category->sort)
                ->horizontal(),

            Label::make('system_id')
                ->title(__('admin.category.parent'))
                ->value($category->parent ? $category->parent->title : null)
                ->horizontal(),

            Label::make('created_at')
                ->title(__('admin.created_at'))
                ->value($category->created_at)
                ->horizontal(),

            Label::make('updated_at')
                ->title(__('admin.updated_at'))
                ->value($category->updated_at)
                ->horizontal(),

            Upload::make('attachment')
                ->title(__('admin.category.image'))
                ->maxFiles(1)
                ->acceptedFiles('image/png,image/jpeg,image/jpg,image/png, image/webp')
                ->maxFileSize(2)
                ->value($category->attachment)
                ->help('Для загрузки доступны изображения в формате webp, jpg, jpeg, png. Максимальный размер изображения 2МБ')
                ->horizontal(),
        ];
    }
}
