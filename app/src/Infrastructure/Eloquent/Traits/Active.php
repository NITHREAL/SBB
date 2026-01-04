<?php

namespace Infrastructure\Eloquent\Traits;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Support\Facades\Toast;

trait Active
{
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(
            sprintf('%s.%s', $this->table, 'active'),
            true,
        );
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where(
            sprintf('%s.%s', $this->table, 'active'),
            false,
        );
    }

    public function activate(bool $active = true): self
    {
        $this->update(['active' => $active]);

        if ($active) {
            Toast::success(__('admin.toasts.activated'));
        } else {
            Toast::success(__('admin.toasts.deactivated'));
        }

        return $this;
    }
}
