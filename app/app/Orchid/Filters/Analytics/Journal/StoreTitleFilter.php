<?php

declare(strict_types=1);

namespace App\Orchid\Filters\Analytics\Journal;

use App\Orchid\Filters\Basic\RelationFilter;
use Domain\Store\Models\Store;

class StoreTitleFilter extends RelationFilter
{
    public $parameters = [
        'stores'
    ];

    protected ?string $dbColumn = 'store_id';

    protected string $modelClassName = Store::class;

    protected string $modelColumnName = 'title';

    protected bool $multiple = true;

    public function name(): string
    {
        return __('admin.journal.store_title');
    }
}
