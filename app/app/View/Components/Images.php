<?php

namespace App\View\Components;

use Domain\Image\Models\Attachment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Images extends Component
{
    public ?Collection $images;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Collection|Attachment $attached = null)
    {
        if ($attached instanceof Attachment) {
            $attached = new Collection([$attached]);
        }

        if (!$attached) {
            $attached = new Collection();
        }

        $this->images = $attached;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
        return view('components.images', [
            'images' => $this->images
        ]);
    }
}
