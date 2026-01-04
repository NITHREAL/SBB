<?php

namespace App\Orchid\Screens\Reports;

use App\Orchid\Core\Actions;
use App\Orchid\Core\Actions\Edit;
use App\Orchid\Helpers\TD\ID;
use Domain\Audience\Models\Audience;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class AudienceScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public string $name = 'Аудитории';

    /**
     * Display header description.
     *
     * @var string
     */
    public string $description = '';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'audiences' => Audience::query()
                ->filters()
                ->defaultSort('id', 'desc')
                ->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Contracts\Actionable[]
     */

    public function commandBar(): array
    {
        return Actions::make([
            new Actions\Create('platform.audiences.create')
        ]);
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     * @throws \Throwable
     *
     */
    public function layout(): array
    {
        return [
            Layout::table('audiences', [
                TD::make('id', '#')->sort(),
                TD::make('title', __('admin.title'))->sort(),
                TD::make('created_at', __('admin.created_at'))->sort()
                    ->render(function (Audience $audience) {
                        return $audience->created_at->format('d-m-Y H:m:s');
                    }),
                TD::make('updated_at', __('admin.updated_at'))->sort()
                    ->render(function (Audience $audience) {
                        return $audience->updated_at->format('d-m-Y H:m:s');
                    }),
                ID::make('users_count', 'Пользователей')->sort(),
                TD::make()->actions([
                    new Edit('platform.audiences.edit'),
                ]),
            ]),

        ];
    }
}
