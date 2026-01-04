<?php

namespace Domain\Audience\Jobs;

use Domain\Audience\Models\Audience;
use Domain\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AudienceRecalculateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1800;

    public function __construct(
        private readonly Audience $audience
    ) {
    }

    public function handle(): void
    {
        $this->audience->users()->detach();

        $filtersData = $this->audience->filter_data;

        $query = User::query();
        $query->select('id');

        foreach ($filtersData as $filter) {
            if ($filter['name'] == 'check_avg') {
                $this->filterCheckAvg($query, $filter);
            }
        }

        $users = $query->pluck('id')->toArray();
        $this->audience->users()->attach($users);

        $usersCount = $this->audience->users()->count();
        $this->audience->update(['users_count' => $usersCount]);
    }

    /**
     * @param Builder $query
     * @param array $filter
     * @return Builder
     */
    protected function filterCheckAvg(Builder $query, array $filter): Builder
    {
        return $query
            ->withAvg('orders', 'total_price')
            ->having('orders_avg_total_price', '>=', $filter['value'] ?? 0);
    }
}
