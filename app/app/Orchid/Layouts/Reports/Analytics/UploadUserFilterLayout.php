<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Reports\Analytics;

use App\Orchid\Filters\User\FirstNameFilter;
use App\Orchid\Filters\User\LastNameFilter;
use App\Orchid\Filters\User\PhoneFilter;
use Orchid\Screen\Layouts\Selection;

class UploadUserFilterLayout extends Selection
{
    public function filters(): array
    {
        return [
            PhoneFilter::class,
            LastNameFilter::class,
            FirstNameFilter::class,
        ];
    }
}
