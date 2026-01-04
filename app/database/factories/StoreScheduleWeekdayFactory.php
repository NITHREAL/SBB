<?php

namespace Database\Factories;

use Domain\Store\Models\StoreScheduleWeekday;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class StoreScheduleWeekdayFactory extends Factory
{
    protected $model = StoreScheduleWeekday::class;

    public function definition(): array
    {
        $weekdays = [
            'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'
        ];
        return [
            'store_id'          => $this->faker->numberBetween(1, 100),
            'polygon_type_id'   => $this->faker->numberBetween(1, 100),
            'week_day'          => Arr::first($this->faker->randomElements($weekdays)),
            'from'              => '10:00:00',
            'to'                => '20:00:00',
            'not_working'       => 0
        ];
    }
}
