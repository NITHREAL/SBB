<?php

namespace App\Orchid\Layouts\Faq\Faq;

use Orchid\Screen\Layout;
use Orchid\Screen\Repository;

class FaqQuestionListTableHeader extends Layout
{
    public function build(Repository $repository): string{
        return '<div class="layout mt-4">
                    <h3>Вопросы категории:<h3>
                </div>';
    }
}
