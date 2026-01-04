<?php

namespace App\Console\Commands\Loyalty\BonusLevel;

use Domain\BonusLevel\Services\BonusLevelService;
use Illuminate\Console\Command;

class BonusLevelsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loyalty:bonus-levels:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление списка бонусных уровней';

    public function handle(BonusLevelService $bonusLevelService): void
    {
        $bonusLevelService->processBonusLevelsUpdate();
    }
}
