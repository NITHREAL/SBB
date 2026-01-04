<?php

namespace App\Orchid\Screens\Content;

use App\Http\Requests\Admin\SlideRequest;
use App\Models\Enums\SlideUserTypesEnum;
use App\Models\Slide;
use App\Models\Slider;
use App\Orchid\Helpers\Fields\CityField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SlideEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавление слайдера';

    public string $route = 'main-slider';
    public string $sliderType = 'main';

    /**
     * @var bool
     */
    public bool $exists = false;

    /**
     * Query data.
     *
     * @param Slide $slide
     * @return array
     */
    public function query(Slide $slide): array
    {
        $this->exists = $slide->exists;

        if ($this->exists) {
            $this->name = 'Редактирование слайдера';
        }

        $slide->load('attachment');
        $slide->load('cities');

        return [
            'slide' => $slide
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make(__('admin.save'))
                ->icon('note')
                ->method('save'),

            Button::make(__('admin.delete'))
                ->icon('trash')
                ->method('delete')
                ->canSee($this->exists),
        ];
    }

    /**
     * Views.
     *
     * @return string[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Label::make('slide.id')
                    ->title(__('admin.id'))
                    ->style('padding: .5rem .75rem;margin: 0;')
                    ->canSee($this->exists)
                    ->horizontal(),
                Input::make('slide.sort')
                    ->title(__('admin.sort'))
                    ->type('number')
                    ->value(500)
                    ->horizontal(),
                Input::make('slide.title')
                    ->title(__('admin.title'))
                    ->type('text')
                    ->required()
                    ->horizontal(),
                CheckBox::make('slide.active')
                    ->title('admin.active')
                    ->sendTrueOrFalse()
                    ->horizontal(),
                CityField::make('slide.cities')
                    ->multiple()
                    ->title(__('admin.slide.city')),
                Select::make('slide.user_type')
                    ->title(__('admin.slide.user_type'))
                    ->options(SlideUserTypesEnum::toArray())
                    ->horizontal(),
                Input::make('slide.url')
                    ->title(__('admin.slide.url'))
                    ->type('text')
                    ->required()
                    ->horizontal(),
                Input::make('slide.button_text')
                    ->title(__('admin.slide.button_text'))
                    ->type('text')
                    ->required()
                    ->canSee($this->sliderType === 'main')
                    ->horizontal(),
                Input::make('slide.mask_color')
                    ->title(__('admin.slide.mask_color'))
                    ->type('color')
                    ->canSee($this->sliderType === 'main')
                    ->horizontal(),
                Upload::make('slide.attachment')
                    ->title(__('admin.image'))
                    ->maxFileSize(2)
                    ->maxFiles(1)
                    ->help('Максимальный размер файла 2 МБ')
                    ->acceptedFiles('image/*')
                    ->horizontal(),
            ])
        ];
    }

    /**
     * @param Slide $slide
     * @param SlideRequest $request
     * @return RedirectResponse
     */
    public function save(Slide $slide, SlideRequest $request): RedirectResponse
    {
        $data = $request->validated()['slide'];

        $slider = Slider::firstWhere('type', $this->sliderType);

        $slide->fill($data);
        $slide->slider()->associate($slider);
        $slide->save();
        $slide->sync(Arr::get($data, 'attachment', []));

        $cities = Arr::get($data, 'cities', []);
        $slide->cities()->sync($cities);

        Toast::success(__('admin.toasts.updated'));

        return redirect()->route("platform.{$this->route}.edit", ['slide' => $slide]);
    }

    /**
     * @param Slide $slide
     * @return RedirectResponse
     */
    public function delete(Slide $slide): RedirectResponse
    {
        $slide->delete();
        Alert::info('Вы успешно удалили слайдер.');
        return redirect()->route("platform.{$this->route}.list");
    }
}
