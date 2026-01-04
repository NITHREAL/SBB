<?php

namespace App\Orchid\Screens\Content\Story;

use App\Orchid\Core\Actions;
use App\Orchid\Fields\SmallCropper;
use Domain\Story\Enums\StoryPageTypeEnum;
use Domain\Story\Models\StoryPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;

class StoryPageEditScreen extends Screen
{
    public ?string $name = 'Создание / редактирование страницы истории';

    public $description = null;

    public ?StoryPage $page = null;

    private ?bool $exists = false;

    public function query(int $parent_id, ?int $id = null): array
    {
        $page = $id ? StoryPage::findOrFail($id) : new StoryPage(['story_id' => $parent_id]);

        $this->exists = $page->exists;

        $this->page = $page;

        return [
            'page' => $this->page
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        $actions = [
            Actions\Save::for($this->page),
        ];

        if ($this->exists) {
            $actions[] = Actions\Delete::for($this->page);
        }

        return Actions::make($actions);
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        $help = "
            *Пояснение по id связанной сущности в контексте типа: \r\n
            Простой - ничего не пишем \r\n
            Акция - slug страницы с акцией (vse-dlya-vas) | Акции пока не реализованы \r\n
            Продукт - slug страницы с продуктом (kolbasa-salyami-klassik) \r\n
            Новость - slug страницы с продуктом (kolbasa-salyami-klassik) | Новости пока не реализованы \r\n
            Внутренняя страница сайта - относительная ссылка (/personal/orders) \r\n
            Внутренняя страница МП - относительная ссылка (/personal/orders) \r\n
            Страница каталога - slug каталога, все что после /catalog/ (kolbasy-sosiski-delikatesy/myasnye-delikatesy) \r\n
            Страница фермера - slug \r\n
            Чат - пока не реализован \r\n
            Внешняя ссылка - любой абсолютный url (https://yandex.ru) \r\n
        ";
        return [
            Layout::columns([
                Layout::rows([
                    Input::make('page.title')
                        ->title('Заголовок')
                        ->placeholder('Заголовок'),
                    Input::make('page.text')
                        ->title('Текст')
                        ->placeholder('Тип'),
                    Input::make('page.label')
                        ->title('Лэйбл')
                        ->placeholder('Введите текст лейбла'),
                    Input::make('page.label_color')
                        ->type('color')
                        ->title('Цвет лейбла')
                        ->placeholder('Укажите цвет лейбла'),
                    Select::make('page.type')
                        ->required()
                        ->title('Тип')
                        ->options(StoryPageTypeEnum::toArray()),
                    Input::make('page.target_id')
                        ->title('Id связанной сущности')
                        ->placeholder('id')
                        ->help('* cм. пояснение'),
                    Input::make('page.target_url')
                        ->title('отдельно ссылки'),
                    Input::make('page.position')
                        ->title('Позиция')
                        ->type('number')
                        ->min(0)
                        ->help('Чем меньше значение, тем выше в списке')
                        ->required(),
                    Input::make('page.timer')
                        ->title('Таймер (сек.)')
                        ->type('number')
                        ->min(0)
                        ->max(126)
                        ->help('Указывает длительность отображения сторис (в секундах)'),
                ]),
                Layout::rows([
                    SmallCropper::make('page.image_id')
                        ->required()
                        ->targetId()
                        ->title('Картинка')
                        ->width(960)
                        ->height(1600),
                    TextArea::make('help')
                        ->value($help)
                        ->readonly()
                        ->rows(26)
                        ->class('form-control no-resize mt-4')
                        ->style('font-size: 12px; background-color: transparent; color: inherit; white-space: pre-line'),
                ]),
            ])

        ];
    }

    public function save(int $parent_id, Request $request): RedirectResponse
    {
        $requestData = $request->get('page');

        $id = (int) Arr::get($request->route()->parameters(), 'id', 0);

        if ($id) {
            $page = StoryPage::findOrFail($id);
        } else {
            $page = new StoryPage();
            $requestData['story_id'] = $parent_id; //Привязка новой страницы к родителю
        }

        $page->fill($requestData);
        $page->save();

        Alert::info('Страница сохранена успешно');

        return redirect()->route('platform.stories.edit', $page->story->id);
    }

    public function delete(int $parent_id, int $id): RedirectResponse
    {
        $page = StoryPage::findOrFail($id);

        $page->delete();

        Alert::info("Удалена запись \"{$page->title}\"");

        return redirect()->route('platform.stories.edit', $parent_id);
    }

}
