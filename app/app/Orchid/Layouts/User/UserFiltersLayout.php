<?php

namespace App\Orchid\Layouts\User;

use App\Orchid\Filters\User\EmailFilter;
use App\Orchid\Filters\User\FirstNameFilter;
use App\Orchid\Filters\User\LastNameFilter;
use App\Orchid\Filters\User\PhoneFilter;
use App\Orchid\Filters\User\RoleFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class UserFiltersLayout extends Selection
{
    /**
     * @return string[]|Filter[]
     */
    public function filters(): array
    {
        return [
            LastNameFilter::class,
            FirstNameFilter::class,
            PhoneFilter::class,
            EmailFilter::class,
        ];
    }
}
