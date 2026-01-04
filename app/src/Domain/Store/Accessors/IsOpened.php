<?php

namespace Domain\Store\Accessors;

use Domain\Store\Models\Store;
use Domain\Store\Models\StoreScheduleDate;
use Domain\Store\Models\StoreScheduleWeekday;
use Illuminate\Support\Carbon;

final class IsOpened
{
    public function __construct(private readonly Store $store)
    {
    }

    public function __invoke(): bool
    {
        $city = $this->store->city;

        if (!$city || !$city->timezone) {
            return false;
        }

        $now = Carbon::now($city->timezone);
        $weekDay = $now->format('l');

        $weekDaySchedule = $this->store->scheduleWeekdays->where('week_day', strtolower($weekDay))->first();
        $dateSchedule = $this->store->scheduleDates->where('date', $now->format('Y-m-d'))->first();

        return $this->checkIsOpened($dateSchedule, $now) || $this->checkIsOpened($weekDaySchedule, $now);
    }

    private function checkIsOpened(StoreScheduleWeekday|StoreScheduleDate|null $schedule, Carbon $now): bool
    {
        if (is_null($schedule) || $schedule->not_working) {
            return false;
        }

        if ($schedule->from && $schedule->to) {
            $from = Carbon::createFromTimeString($schedule->from, $now->getTimezone());
            $to   = Carbon::createFromTimeString($schedule->to, $now->getTimezone());

            return $now->between($from, $to);
        }

        return false;
    }
}
