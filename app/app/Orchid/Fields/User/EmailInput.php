<?php

namespace App\Orchid\Fields\User;

use Orchid\Screen\Fields\Input;

class EmailInput extends Input
{
    public function __construct()
    {
        parent::__construct();

        $this->title(__('admin.user.email'));
        $this->type('email');
    }
}
