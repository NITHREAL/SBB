<?php

namespace App\Orchid\Screens\Support;

use App\Orchid\Filters\Support\TypeFilter;
use App\Orchid\Layouts\JsAutoUpdateLayout;
use App\Orchid\Layouts\Support\SupportFilterLayout;
use App\Orchid\Layouts\Support\SupportListLayout;
use Domain\User\Models\User;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class SupportScreen extends Screen
{
    public $name = 'Чаты с покупателями';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Request $request): array
    {
        $sortColumn = ltrim($request->get('sort', '-newCount'), '-');
        $sortDirection = $request->get('sort', '-newCount')[0] === '-' ? 'desc' : 'asc';

        $users = User::filtersApply([TypeFilter::class])
            ->has('supportMessages')
            ->get()
            ->map(function ($row) {
                $messages = $row->supportMessages->where('author', 'user');
                $row->count = $messages->count();
                $row->newCount = $messages->where('viewed', false)->count();
                return $row;
            })
            ->sortBy($sortColumn, SORT_REGULAR, $sortDirection === 'desc')

            ->values();

        return [
            'user' => $users,
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            SupportFilterLayout::class,
            SupportListLayout::class,
            JsAutoupdateLayout::class,
        ];
    }
}
