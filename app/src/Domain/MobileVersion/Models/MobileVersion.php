<?php

namespace Domain\MobileVersion\Models;

use App\Orchid\Core\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class MobileVersion extends Model
{
    use HasFactory;
    use AsSource;
    use Filterable;

    protected $fillable = [
        'version',
        'status',
        'platform'
    ];
}
