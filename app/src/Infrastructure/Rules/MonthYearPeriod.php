<?php

namespace Infrastructure\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class MonthYearPeriod implements ValidationRule
{
    private string $format = '/^(0?[1-9]|1[012])(-)([0-9][0-9])$/'; // mm-yy

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (!preg_match($this->format, $value)) {
            $fail('Неверный формат периода месяц-год');
        }
    }
}
