<?php

namespace App\Orchid\Layouts\Shop\FarmerQuestionnaire;

use App\Orchid\Core\Actions;
use App\Orchid\Helpers\TD\DateTime;
use App\Orchid\Helpers\TD\ID;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class FarmerQuestionnaireListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'farmer_questionnaires';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            ID::make(),

            TD::make('fio', __('admin.farmer_questionnaire.fio'))
                ->filter(TD::FILTER_TEXT)
                ->sort(),
            TD::make('phone', __('admin.farmer_questionnaire.phone'))
                ->filter(TD::FILTER_TEXT)
                ->sort(),
            TD::make('email', __('admin.farmer_questionnaire.email'))
                ->filter(TD::FILTER_TEXT)
                ->sort(),

            DateTime::createdAt(),
            DateTime::updatedAt(),

            TD::make()->actions([
                new Actions\Show('platform.farmer_questionnaires.show'),
            ])
        ];
    }
}
