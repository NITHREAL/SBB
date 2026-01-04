<?php

namespace App\Orchid\Screens\Traits;

use Illuminate\Database\Eloquent\Model;

trait SyncHasMany
{
    public function syncHasMany(Model $model, string $relation, array $items): void
    {
        $receivedIds = array_column($items, 'hash');
        $storedIds = $model->$relation->pluck('hash')->toArray();
        $removedIds = array_diff($storedIds, $receivedIds);

        $model->$relation()->whereIn('hash', $removedIds)->delete();

        foreach ($items as $item) {
            $model->$relation()->updateOrCreate([
                'hash' => $item['hash']
            ], $item);
        }
    }
}
