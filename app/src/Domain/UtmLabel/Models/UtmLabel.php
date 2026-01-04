<?php

namespace Domain\UtmLabel\Models;

use App\Orchid\Core\Filters\Filterable as AdminFilterable;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class UtmLabel extends Model
{
    use AsSource;
    use AdminFilterable;

    protected $table = 'utm_labels';

    protected $fillable = [
        'type',
        'value',
        'description',
    ];
}
