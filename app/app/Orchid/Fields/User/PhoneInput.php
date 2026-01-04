<?php

namespace App\Orchid\Fields\User;

use Orchid\Screen\Fields\Input;

class PhoneInput extends Input
{
    public function __construct()
    {
        parent::__construct();

        $this->title(__('admin.user.phone'));
        $this->mask('+7(999)999-99-99');
        $this->type('tel');
    }
}
