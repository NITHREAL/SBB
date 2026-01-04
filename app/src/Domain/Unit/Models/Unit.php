<?php

namespace Domain\Unit\Models;

use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable as AdminFilterable;
use Orchid\Screen\AsSource;

class Unit extends Model
{
    use HasFactory;
    use AdminFilterable;
    use AsSource;
    use HasFactory;

    protected $fillable = [
        'system_id',
        'title'
    ];

    protected static function newFactory(): Factory
    {
        return UnitFactory::new();
    }
}
