<?php

use App\Models\PolygonType;
use App\Models\StoreScheduleDate;
use App\Models\StoreScheduleWeekday;
use Database\Seeders\PolygonTypesSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Command\Command as CommandAlias;

class UpdateCorrectPolygonTypesStoreSchedule extends Migration
{
    private $countPerAction = 250;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->handle();
    }

    public function handle()
    {
        // TODO после добавления полигонов реализовать эту логику

//        /**
//         * @var PolygonType[]|Collection $polygonTypes
//         */
//
//        $polygonTypes = PolygonType::all();
//
//        if (count($polygonTypes) <= 0) {
//            $seeder = new PolygonTypesSeeder();
//            $seeder->run();
//
//            $polygonTypes = PolygonType::all();
//        }
//
//        $firstPolygonType = $polygonTypes->first();
//        $polygonTypes->shift();
//
//        $this->handleWeekdays($firstPolygonType, $polygonTypes);
//        $this->handleDates($firstPolygonType, $polygonTypes);
    }

    /**
     * @param Collection|PolygonType[] $polygonTypes
     * @return void
     */
    private function handleWeekdays($firstPolygonType, Collection|array $polygonTypes): void
    {
        $countWeekdays = StoreScheduleWeekday::query()->count();

        for ($a = 0; $a <= ($countWeekdays / $this->countPerAction) + 1; $a++) {
            $weekdays = StoreScheduleWeekday::query()
                ->limit($this->countPerAction)
                ->offset($a * $this->countPerAction)
                ->get();

            /**
             * @var StoreScheduleWeekday[] $weekdays
             */
            try {
                DB::beginTransaction();
                $this->saveData($weekdays, $firstPolygonType, $polygonTypes);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
        }
    }


    /**
     * @param Collection|PolygonType[] $polygonTypes
     * @return void
     */
    private function handleDates($firstPolygonType, Collection|array $polygonTypes): void
    {
        $countDates = StoreScheduleDate::query()->count();
        for ($a = 0; $a <= ($countDates / $this->countPerAction) + 1; $a++) {
            $dates = StoreScheduleDate::query()
                ->limit($this->countPerAction)
                ->offset($a * $this->countPerAction)
                ->get();

            /**
             * @var StoreScheduleDate[] $dates
             */
            try {
                DB::beginTransaction();
                $this->saveData($dates, $firstPolygonType, $polygonTypes);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
        }
    }

    /**
     * @param Collection|StoreScheduleDate[]|StoreScheduleWeekday[] $data
     * @param PolygonType $firstPolygonType
     * @param Collection|PolygonType[] $polygonTypes
     */
    private function saveData(
        Collection|array $data,
        PolygonType $firstPolygonType,
        Collection|array $polygonTypes
    ) {
        /**
         * @var StoreScheduleWeekday|StoreScheduleDate $item
         */
        foreach ($data as $item) {
            if ($item->polygon_type_id) {
                continue;
            }

            $item->polygon_type_id = $firstPolygonType->id;
            $item->update();
            foreach ($polygonTypes as $polygonType) {
                $newItem = $item->replicate();
                $newItem->push();
                $newItem->polygon_type_id = $polygonType->id;
                $newItem->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
