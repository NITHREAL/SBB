<?php

namespace App\Orchid\Layouts\Content\Story;

use Orchid\Screen\Layout;
use Orchid\Screen\Repository;

class StoryPageListTableBlockHeader extends Layout
{
    public function build(Repository $repository): string{
        return '<div class="layout mt-4">
                    <h3>Страницы истории:<h3>
                </div>';
    }
}
