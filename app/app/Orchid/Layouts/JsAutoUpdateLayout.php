<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layout;
use Orchid\Screen\Repository;

class JsAutoUpdateLayout extends Layout
{
    /**
     * Вставляет на страницу код для автообновления
     * чтобы поюзать - забиваем в query вызывающего шаблон screen элемент с ключом "interval"
     */
    public function build(Repository $repository): string
    {
        $interval = (isset($repository['interval']) && is_integer($repository['interval']))
            ? $repository['interval'] : 60;
        // $needScrollDown = (isset($repository['scrollDown']) && $repository['scrollDown'] === true);
        ob_start();?>
        <div id="scroll-here"></div>
            <script>
                let interval = <?=$interval * 1000?>;
                setInterval(() => {location.reload()}, interval)
                    // const element = document.getElementById('scroll-here');
                    // element.scrollTop = element.scrollHeight;
            </script>
        <?php
        return ob_get_clean();
    }
}
