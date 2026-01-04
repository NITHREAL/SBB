<?php

namespace Domain\Exchange\Rules;

use Illuminate\Contracts\Validation\Rule;

class Base64 implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^[a-zA-Z0-9\/+]*={0,2}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.base64');
    }
}
