<?php

namespace App\View\Components;

use Domain\Image\Models\Attachment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Preview extends Component
{
    public ?Attachment $image = null;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Attachment $image = null)
    {
        $this->image = $image;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
        return view('components.preview', [
            'image' => $this->image
        ]);
    }
}
