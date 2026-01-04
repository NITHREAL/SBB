<?php

namespace App\Orchid\Filters\User;

use App\Orchid\Filters\Basic\RelationFilter;
use Domain\User\Models\User;

class UserFilter extends RelationFilter
{
    public $parameters = [
        'users'
    ];

    protected ?string $dbColumn = 'user_id';

    protected string $modelClassName = User::class;

    protected string $modelColumnName = 'id';

    protected array $modelSearchColumns = ['first_name', 'last_name', 'phone'];

    protected ?string $modelDisplayAppend = 'name_with_phone';

    protected bool $multiple = true;

    public function name(): string
    {
        return __('admin.user.user');
    }
}
