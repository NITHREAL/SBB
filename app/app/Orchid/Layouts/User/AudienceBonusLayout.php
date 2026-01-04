<?php

namespace App\Orchid\Layouts\User;

use Domain\Audience\Models\Audience;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;

class AudienceBonusLayout extends Rows
{
    /**
     * Views.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Relation::make('audience_id')
                ->fromModel(Audience::class, 'title')
                ->title('Аудитория')
                ->required(),
            Input::make('bonus')
                ->type('number')
                ->title('Бонусы')
                ->required(),
        ];
    }
}
