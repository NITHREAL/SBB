<?php

namespace Domain\Order\Services\Delivery\DateServices;

use Domain\Order\Models\Delivery\PolygonType;
use Domain\Store\Models\Store;
use Exception;
use Illuminate\Support\Carbon;
use Infrastructure\Enum\Timezone;

class ReceiveInterval
{
    public const PATTERN = '/([0-9]|1[0-9]|2[0-3])_([0-9]|1[0-9]|2[0-3])/i';
    private const DAYS_COUNT = 2;

    private const CANNOT_PRE_ORDER_DAYS = 2;

    private const DEFAULT_STORE_START_OF_DAY = 9;

    private const DEFAULT_STORE_END_OF_DAY = 21;

    private Store $store;
    private string $tz;
    private ?PolygonType $polygonType = null;

    public function __construct(Store $store, PolygonType|string|null $polygonType = null, $deliveryType=null)
    {
        $store->loadMissing('city', 'scheduleWeekdays', 'scheduleDates');

        $this->store = $store;
        $this->tz = $this->defineTimezone();
        if (!is_null($polygonType)) {
            if (is_string($polygonType)  && !is_null($deliveryType )) {
                $this->polygonType = PolygonType::query()->where(['type' => $polygonType])->where(['delivery_type' => $deliveryType])->first();
            }
            elseif (is_string($polygonType)) {
                $this->polygonType = PolygonType::query()->where(['type' => $polygonType])->first();
            }
            else {
                $this->polygonType = $polygonType;
            }
        }
    }

    private function defineTimezone(): string
    {
        $tz = config('app.timezone');

        if ($this->store->city) {
            $tzEnum = Timezone::tryFrom($this->store->city->timezone);

            if ($tzEnum) {
                $tz = $tzEnum->value;
            }
        }

        return $tz;
    }

    /**
     * Получить интервал дня
     *
     * @param Carbon $day
     * @param int $step
     * @param bool $not31Jan
     * @return array
     */
    public function getInterval(Carbon $day, int $step = 1, bool $not31Jan = false): array
    {
        $days = [];
        $today = Carbon::now()->setTimezone($this->tz); // сегодняшняя дата (важно точное время)
        //$today->add(5, 'hour'); // Для отладки вечером
        $timeIntervals = $this->getTimeIntervals($today, $step);

        $i = 1;
        if (self::isAvailableDate($today, $not31Jan) && $day->isToday() && $timeIntervals) {
            $days[] = [
                'date' => $today->format('Y-m-d'),
                'intervals' => $timeIntervals
            ];
        } else {
            if (!$day->isToday()){
                $i = 0;
            }
        }

        // тут уже перебираются следующие даты, время - с нуля часов
        for (; $i < self::DAYS_COUNT; $i++) {
            $currentDay = clone $day;
            $currentDay->floorDay(); //сбрасвывем время в 00:00:00 чтобы следующие даты считались и после закрытия магазина
            $currentDay->add($i, 'day');
            $timeIntervals = $this->getTimeIntervals($currentDay, $step);
            if (self::isAvailableDate($currentDay, $not31Jan) && $timeIntervals) {
                $days[] = [
                    'date' => $currentDay->format('Y-m-d'),
                    'intervals' => $timeIntervals
                ];
            }

        }

        return $days;
    }

    public function getPickupInterval(Carbon $day): array
    {
        $days = [];

        $today = Carbon::now()->setTimezone($this->tz); // сегодняшняя дата (важно точное время)
        //$today->add(5, 'hour'); // Для отладки вечером

        $timeIntervals = $this->getPickupTimeIntervals($day);

        $i = 1;

        if (self::isAvailableDate($today) && $day->isToday() && $timeIntervals) {
            $days[] = [
                'date'      => $today->format('Y-m-d'),
                'intervals' => $timeIntervals
            ];
        } elseif (!$day->isToday()) {
            $i = 0;
        }

        // тут уже перебираются следующие даты, время - с нуля часов
        for (; $i < self::DAYS_COUNT; $i++) {
            $newDay = clone $day;
            $newDay->floorDay(); //сбрасвывем время в 00:00:00 чтобы следующие даты считались и после закрытия магазина
            $newDay->add($i, 'day');

            $timeIntervals = $this->getPickupTimeIntervals($newDay);
            if (self::isAvailableDate($newDay) && $timeIntervals) {
                $days[] = [
                    'date'      => $newDay->format('Y-m-d'),
                    'intervals' => $timeIntervals
                ];
            }
        }

        return $days;
    }

    /**
     * Получить инте
     * @param Carbon $day
     * @return string
     */
    public function getIntervalAllDay(Carbon $day): string
    {
        $storeSchedule = $this->getStoreSchedule($day);

        $from = $storeSchedule
            ? (int) $storeSchedule->from
            : self::DEFAULT_STORE_START_OF_DAY;
        $to = $storeSchedule
            ? (int) $storeSchedule->to
            : self::DEFAULT_STORE_END_OF_DAY;

        return $this->getPreparedTimeInterval($from, $to);
    }

    public function getStoreSchedule(Carbon $day)
    {
        $weekDay = $day->format('l');
        $date = $day->format('Y-m-d');

        $storeScheduleWeekdays = $this->store->scheduleWeekdays
            ->where('week_day', strtolower($weekDay))
            ->whereNotNull('from')
            ->whereNotNull('to')
            ->where('not_working', false)
            ->when($this->polygonType, function ($q) {
                return $q->where('polygon_type_id', $this->polygonType?->id);
            })
            ->first();

        // $this->store->scheduleWeekdays->w
        $storeScheduleDate = $this->store->scheduleDates
            ->where('date', $date)
            ->whereNotNull('from')
            ->whereNotNull('to')
            ->where('not_working', false)
            ->when($this->polygonType, function ($q) {
                return $q->where('polygon_type_id', $this->polygonType?->id);
            })
            // ->where('polygon_type_id', $this->polygonType?->id)
            ->first();

        return $storeScheduleDate ?? $storeScheduleWeekdays;
    }

    public function getIntervalByPreOrder(array $deliveryDates): array
    {
        $currentDay = Carbon::now()
            ->addDays(self::CANNOT_PRE_ORDER_DAYS)
            ->setTimezone($this->tz);
        $days = [];

        for ($i = 0; $i < self::DAYS_COUNT; $i++) {
            if (
                self::isAvailableDate($currentDay) &&
                in_array($currentDay->format('Y-m-d'), $deliveryDates, true)
            ) {
                $days[] = [
                    'date' => $currentDay->format('Y-m-d'),
                    'intervals' => $this->getTimeIntervals($currentDay)
                ];
            }

            $currentDay->addDay();
        }

        return $days;
    }

    /**
     * @param Carbon $day
     * @param int $step
     * @return array
     */
    public function getTimeIntervals(Carbon $day, int $step = 1): array
    {
        $timeIntervals = [];

        $schedule = $this->getStoreSchedule($day);
        if (!$schedule) {
            return $timeIntervals;
        }

        $maxHour = (int) $schedule->to;
        $from = (int) $schedule->from;
        $to = min($from + $step, $maxHour);

        while ($from < $maxHour) {
            $intervalIsAvailable = $this->isIntervalAvailable($day, $from, $to, $maxHour, $step);

            if ($intervalIsAvailable) {
                $timeIntervals[] = $this->getPreparedTimeIntervalData($day, $from, $to);
            }

            if ($to === $maxHour) {
                break;
            }

            $from += $step;
            $to = min($to + $step, $maxHour);
        }

        return $timeIntervals;
    }

    public function getPickupTimeIntervals(Carbon $day): array
    {
        $result = [];

        $storeSchedule = $this->getStoreSchedule($day);

        if ($storeSchedule) {
            $from = (int) $storeSchedule->from ?? self::DEFAULT_STORE_START_OF_DAY;
            $to = (int) $storeSchedule->to ?? self::DEFAULT_STORE_END_OF_DAY;

            $intervalIsAvailable = !$day->isToday() || $this->isTodayPickupIntervalAvailable($day, $to);

            if ($intervalIsAvailable) {
                $result[] = [
                    'value' => $this->getPreparedTimeInterval($from, $to),
                    'label' => self::getIntervalLabel($from, $to),
                    'from'  => $day->clone()->hour($from)->minute(0)->second(0)->getTimestamp(),
                    'to'    => $day->clone()->hour($to)->minute(0)->second(0)->getTimestamp(),
                ];
            }
        }

        return $result;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getNearestTimeInterval(): array
    {
        $currentDay = Carbon::now()->setTimezone($this->tz);

        $timeIntervals = $this->getTimeIntervals($currentDay);

        if ($timeIntervals) {
            return [
                'date' => $currentDay->format('Y-m-d'),
                'interval' => $timeIntervals[0]['value']
            ];
        }

        for ($plusDay = 0; $plusDay < self::DAYS_COUNT; $plusDay++) {
            $currentDay->addDay()->setHour(0)->setMinute(0);

            $timeIntervals = $this->getTimeIntervals($currentDay);

            if ($timeIntervals) {
                return [
                    'date' => $currentDay->format('Y-m-d'),
                    'interval' => $timeIntervals[0]['value']
                ];
            }
        }

        throw new Exception('Не найдено ближайшее время получения заказа.');
    }

    /**
     * @param string|int $from
     * @param string|int $to
     * @return string
     */
    public static function getIntervalLabel(string|int $from, string|int $to): string
    {
        $from = substr('0' . $from, -2);
        $to   = substr('0' . $to, -2);

        return "{$from}:00 - {$to}:00";
    }

    /**
     * @param string|null $interval
     * @return string|null
     */
    public static function labelFromString(string $interval = null): ?string
    {
        $label = null;

        if ($interval && self::validate($interval)) {
            [$from, $to] = explode('_', $interval);

            $label = self::getIntervalLabel($from, $to);
        }

        return $label;
    }

    public static function validate(string $interval): bool
    {
        return (bool)preg_match(self::PATTERN, $interval);
    }

    public static function isAvailableDate(Carbon|string $date, $not31jan = false): bool
    {

        if (is_string($date)) {
            $date = Carbon::createFromFormat('Y-m-d', $date);
        }

        $day   = $date->day;
        $month = $date->month;

        // new year holidays
        if ($not31jan) {
            if ($day === 31 && $month === 12) {
                return false;
            }
        }
        if (
            (in_array($day, [1, 2]) && $month === 1)
        ) {
            return false;
        }

        return true;
    }

    private function getPreparedTimeInterval(int $from, int $to): string
    {
        return sprintf('%s_%s', $from, $to);
    }

    private function getPreparedTimeIntervalData(Carbon $day, int $from, int $to): array
    {
        return [
            'value' => $this->getPreparedTimeInterval($from, $to),
            'label' => self::getIntervalLabel($from, $to),
            'from'  => $day->clone()->hour($from)->minute(0)->second(0)->getTimestamp(),
            'to'    => $day->clone()->hour($to)->minute(0)->second(0)->getTimestamp(),
        ];
    }

    private function isIntervalAvailable(
        Carbon $day,
        int $from,
        int $to,
        int $maxHour,
        int $step,
    ): bool {
        return $day->isToday()
            ? $this->isTodayIntervalAvailable($day, $from, $to, $step)
            : $this->isOtherDayIntervalAvailable($day, $maxHour, $step);
    }

    private function isTodayIntervalAvailable(
        Carbon $day,
        int $from,
        int $to,
        int $step,
    ): bool {
        $currentHour = $day->hour;
        $notLate = $to - $currentHour >= $step;
        $notEarly = $from >= $currentHour + 1;

        return $notLate && $notEarly;
    }

    private function isTodayPickupIntervalAvailable(
        Carbon $day,
        int $to,
    ): bool {
        return $to > $day->hour + 1;
    }

    private function isOtherDayIntervalAvailable(
        Carbon $day,
        int $maxHour,
        int $step,
    ): bool {
        $currentHour = $day->hour;

        return $maxHour - $currentHour >= $step;
    }
}
