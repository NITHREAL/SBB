<?php

namespace App\Orchid\Fields\User;

use Orchid\Screen\Fields\Input;

class LastNameInput extends Input
{
    public function __construct()
    {
        parent::__construct();

        $this->title(__('admin.user.last_name'));
        $this->type('text');
    }
}
