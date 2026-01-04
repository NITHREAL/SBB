<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneOrEmail implements Rule
{
    public function passes($attribute, $value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false ||
            preg_match('/^\+?[1-9]\d{9,14}$/', $value);
    }

    public function message()
    {
        return __('validation.store.contacts.phone_or_email');
    }
}
