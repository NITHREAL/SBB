<?php

namespace App\Orchid\Screens\Shop\FarmerQuestionnaire;

use App\Models\FarmerQuestionnaire;
use App\Orchid\Helpers\Sight\DateTimeSight;
use App\Orchid\Helpers\Sight\IdSight;
use App\Orchid\Helpers\Sight\ImagesSight;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class FarmerQuestionnaireShowScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Просмотр анкеты фермера';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(FarmerQuestionnaire $farmerQuestionnaire): array
    {
        if ($farmerQuestionnaire->exists) {
            $farmerQuestionnaire->load('images');
        }

        return [
            'farmer_questionnaire' => $farmerQuestionnaire,
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
            Layout::legend('farmer_questionnaire', [
                IdSight::make(),
                Sight::make('fio', __('admin.farmer_questionnaire.fio')),
                Sight::make('phone', __('admin.farmer_questionnaire.phone')),
                Sight::make('email', __('admin.farmer_questionnaire.email')),
                Sight::make('your_products', __('admin.farmer_questionnaire.your_products')),
                Sight::make('about_you', __('admin.farmer_questionnaire.about_you')),
                Sight::make('your_business', __('admin.farmer_questionnaire.your_business')),
                Sight::make('your_learn', __('admin.farmer_questionnaire.your_learn')),
                Sight::make('legal_about', __('admin.farmer_questionnaire.legal_about')),
                Sight::make('legal_name', __('admin.farmer_questionnaire.legal_name')),
                Sight::make('your_employees', __('admin.farmer_questionnaire.your_employees')),
                Sight::make('production_volume', __('admin.farmer_questionnaire.production_volume')),
                Sight::make('main_directions', __('admin.farmer_questionnaire.main_directions')),
                Sight::make('your_location', __('admin.farmer_questionnaire.your_location')),
                Sight::make('need_workshop', __('admin.farmer_questionnaire.need_workshop')),
                Sight::make('valid_documents', __('admin.farmer_questionnaire.valid_documents')),
                Sight::make('responsible_for_quality', __('admin.farmer_questionnaire.responsible_for_quality')),
                Sight::make('cause', __('admin.farmer_questionnaire.cause')),
                Sight::make('difficulty', __('admin.farmer_questionnaire.difficulty')),
                Sight::make('progress', __('admin.farmer_questionnaire.progress')),
                Sight::make('future', __('admin.farmer_questionnaire.future')),
                Sight::make('plans_future', __('admin.farmer_questionnaire.plans_future')),
                Sight::make('comment', __('admin.farmer_questionnaire.comment')),
                ImagesSight::make('images', __('admin.farmer_questionnaire.images')),
                DateTimeSight::createdAt(),
                DateTimeSight::updatedAt(),
            ]),
        ];
    }
}
