<?php

namespace App\Orchid\Filters\AbandonedBaskets;

use App\Orchid\Filters\Basic\RelationFilter;
use Domain\Basket\Models\Basket;

class BasketFilter extends RelationFilter
{
    public $parameters = [
        'baskets'
    ];

    protected ?string $dbColumn = 'id';

    protected string $modelClassName = Basket::class;

    protected string $modelColumnName = 'id';

    protected array $modelSearchColumns = ['id'];

    protected ?string $modelDisplayAppend = 'id';

    protected bool $multiple = true;

    public function name(): string
    {
        return "";
    }
}
