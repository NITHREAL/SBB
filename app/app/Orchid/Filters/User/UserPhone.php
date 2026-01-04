<?php

declare(strict_types=1);

namespace App\Orchid\Filters\User;

use App\Orchid\Filters\Basic\RelationFilter;
use Domain\User\Models\User;

class UserPhone extends RelationFilter
{
    public $parameters = [
        'user_phones'
    ];

    protected ?string $dbColumn = 'user_id';

    protected string $modelClassName = User::class;

    protected string $modelColumnName = 'phone';

    protected bool $multiple = true;

    public function name(): string
    {
        return __('admin.journal.phone');
    }
}
