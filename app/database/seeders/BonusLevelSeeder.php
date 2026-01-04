<?php

namespace Database\Seeders;

use Domain\BonusLevel\Models\BonusLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BonusLevelSeeder extends Seeder
{
    public function run()
    {
        if (!Schema::hasTable('bonus_levels')) {
            $this->command->warn('Таблицы bonus_levels не существуют. Пропуск выполнения сидера.');
            return;
        }

        $levels = [
            [
                'number' => '1',
                'title' => 'Кешбек 1%',
                'min_bonus_points' => 0,
                'max_bonus_points' => 499,
                'loyalty_id' => 1,
            ],
            [
                'number' => '2',
                'title' => 'Кешбек 2%',
                'min_bonus_points' => 500,
                'max_bonus_points' => 999,
            ],
            [
                'number' => '3',
                'title' => 'Кешбек 3%',
                'min_bonus_points' => 1000,
                'max_bonus_points' => 1999,
            ],
            [
                'number' => '4',
                'title' => 'Кешбек 4%',
                'min_bonus_points' => 2000,
                'max_bonus_points' => 10000,
            ],
        ];

       foreach ($levels as $level) {
    // Добавляем loyalty_id в массив данных перед сохранением
    $level['loyalty_id'] = 1;

    BonusLevel::updateOrCreate(
        ['number' => $level['number']],
        $level
    );
}
    }
}

