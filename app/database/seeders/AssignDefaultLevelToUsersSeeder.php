<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\BonusLevel\Models\BonusLevel;
use Domain\User\Models\User;
use Illuminate\Database\Seeder;

class AssignDefaultLevelToUsersSeeder extends Seeder
{
    private const DEFAULT_USER_BONUS_LEVEL = 2;

    public function run(): void
    {
        $defaultLevel = BonusLevel::whereNumber(self::DEFAULT_USER_BONUS_LEVEL)->first();

        if (!$defaultLevel) {
            $this->command->error('Уровень не найден.');
            return;
        }

        // Проходим по всем пользователям, у которых нет уровня
        User::doesntHave('bonusLevel')->chunk(100, function ($users) use ($defaultLevel) {
            foreach ($users as $user) {
                $user->bonusLevel()->attach($defaultLevel->id, [
                    'current_bonus_points' => $defaultLevel->min_bonus_points,
                ]);
            }
        });

        $this->command->info('Пользователям без уровня назначен второй уровень.');
    }
}

