<?php

namespace Infrastructure\Eloquent\Models;

use Baum\Node;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

abstract class NestedModel extends Node
{
    protected $idColumn = 'system_id';
    protected $parentColumn = 'parent_system_id';
    protected $leftColumn = 'margin_left';
    protected $rightColumn = 'margin_right';
    protected $depthColumn = 'level';

    /**
     * Parent relation (self-referential) 1-1.
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(get_class($this), $this->getParentColumnName(), 'system_id');
    }

    /**
     * Children relation (self-referential) 1-N.
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(get_class($this), $this->getParentColumnName(), 'system_id')
            ->orderBy($this->getOrderColumnName());
    }

    protected function getFreshInstance()
    {
        if ($this->areSoftDeletesEnabled()) {
            return static::withTrashed()->find($this->getKey());
        }

        return static::find($this->getKey()) ?? static::where('id', $this->getKey())->first();
    }
}
