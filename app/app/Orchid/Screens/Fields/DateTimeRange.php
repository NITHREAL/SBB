<?php

namespace App\Orchid\Screens\Fields;

use Orchid\Screen\Fields\DateRange;

class DateTimeRange extends DateRange
{
    /**
     * @var string
     */
    protected $view = 'fields.dataTimeRange';

    /**
     * Enables time picker.
     *
     * @param bool $time
     *
     * @return self
     */
    public function enableTime(bool $time = true): self
    {
        $this->set('data-datetime-enable-time', var_export($time, true));

        return $this;
    }

    /**
     * Displays time picker in 24 hour mode without AM/PM selection when enabled.
     *
     * @param bool $time
     *
     * @return self
     */
    public function format24hr(bool $time = true): self
    {
        $this->set('data-datetime-time-24hr', var_export($time, true));

        return $this;
    }

    public function makeStatic(bool $isStatic = true): self
    {
        $this->set('data-datetime-static', var_export($isStatic, true));

        return $this;
    }
}
