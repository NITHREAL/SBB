<?php

namespace App\Orchid\Core;

use App\Orchid\Fields\User\PhoneInput;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;

class TD extends \Orchid\Screen\TD
{
    public const FILTER_PHONE = 'phone';

    /**
     * @param string $filter
     *
     * @return \Orchid\Screen\Field
     */
    protected function detectConstantFilter(string $filter): Field
    {
        if ($filter === self::FILTER_DATE) {
            return DateTimer::make()->inline()->format('Y-m-d');
        }

        if ($filter === self::FILTER_PHONE) {
            return PhoneInput::make()->inline();
        }

        return Input::make()->type($filter);
    }
}
