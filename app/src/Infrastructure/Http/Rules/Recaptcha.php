<?php

namespace Infrastructure\Http\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Infrastructure\Services\Captcha\CaptchaInterface;

class Recaptcha implements ValidationRule
{
    public function __construct(
        protected CaptchaInterface $captcha
    ) {
    }

    /**
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $recaptchaResponse = $this->captcha->send($value);

        if ($recaptchaResponse['success'] === false) {
            $fail(__('validation.captcha', ['attribute' => $attribute]));
        }
    }
}
