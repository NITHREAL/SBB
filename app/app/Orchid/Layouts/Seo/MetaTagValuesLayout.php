<?php

namespace App\Orchid\Layouts\Seo;

use Domain\MetaTag\Models\MetaTagValues;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class MetaTagValuesLayout extends Rows
{
    protected MetaTagValues|null $metaTagValues;

    public function __construct(MetaTagValues|null $metaTagValues)
    {
        $this->metaTagValues = $metaTagValues;
    }

    protected function fields(): array
    {
        return [
            Input::make('meta_tag_values.title')
                ->type('text')
                ->max(255)
                ->title(__('admin.meta_tag_values.title'))
                ->horizontal()
                ->value($this->metaTagValues?->title)
                ->placeholder(__('admin.meta_tag_values.title')),
            Input::make('meta_tag_values.description')
                ->type('text')
                ->max(255)
                ->title(__('admin.meta_tag_values.description'))
                ->horizontal()
                ->value($this->metaTagValues?->description)
                ->placeholder(__('admin.meta_tag_values.description')),
            Input::make('meta_tag_values.keywords')
                ->type('text')
                ->max(255)
                ->title(__('admin.meta_tag_values.keywords'))
                ->horizontal()
                ->value($this->metaTagValues?->keywords)
                ->placeholder(__('admin.meta_tag_values.keywords')),
            Input::make('meta_tag_values.header_one')
                ->type('text')
                ->max(255)
                ->title(__('admin.meta_tag_values.header_one'))
                ->horizontal()
                ->value($this->metaTagValues?->header_one)
                ->placeholder(__('admin.meta_tag_values.header_one')),
            Select::make('meta_tag_values.header_type')
                ->options([
                    'h1' => 'h1',
                    'h2' => 'h2',
                    'h3' => 'h3',
                    'h4' => 'h4',
                    'h5' => 'h5',
                    'h6' => 'h6',
                ])
                ->horizontal()
                ->value($this->metaTagValues?->header_type)
                ->title(__('admin.meta_tag_values.header_type'))
                ->help('Выберите тег, в котором будет содержаться данный заголовок')
        ];
    }
}
