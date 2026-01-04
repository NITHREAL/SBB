<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OnlyCyrillicSymbols implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('/^[А-яЁё]+$/iu', $value);
    }

    public function message()
    {
        return __('validation.only_cyrillic_symbols');
    }
}
