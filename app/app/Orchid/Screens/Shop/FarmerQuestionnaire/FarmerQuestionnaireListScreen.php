<?php

namespace App\Orchid\Screens\Shop\FarmerQuestionnaire;

use App\Models\FarmerQuestionnaire;
use App\Orchid\Layouts\Shop\FarmerQuestionnaire\FarmerQuestionnaireListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class FarmerQuestionnaireListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список анкет фермеров';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'farmer_questionnaires' => FarmerQuestionnaire::filters()
                ->defaultSort('id', 'desc')
                ->paginate()
        ];
    }

    /**
     * Button commands
     *
     * @return array
     */
    public function commandBar() : array
    {
        return [
            Link::make(__('admin.export'))
                ->route('export', [
                    'type' => 'farmer_questionnaires'
                ]),
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
            FarmerQuestionnaireListLayout::class
        ];
    }
}
