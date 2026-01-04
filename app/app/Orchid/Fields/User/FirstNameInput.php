<?php

namespace App\Orchid\Fields\User;

use Orchid\Screen\Fields\Input;

class FirstNameInput extends Input
{
    public function __construct()
    {
        parent::__construct();

        $this->title(__('admin.user.first_name'));
        $this->type('text');
    }
}
