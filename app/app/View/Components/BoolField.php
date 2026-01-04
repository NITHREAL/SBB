<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BoolField extends Component
{
    public bool $bool;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($bool)
    {
        $this->bool = $bool;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.bool-field', [
            'bool' => $this->bool
        ]);
    }
}
