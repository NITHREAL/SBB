<?php

namespace Infrastructure\Rules;

use Illuminate\Contracts\Validation\Rule;
use Infrastructure\Services\Auth\Exceptions\InvalidSignatureException;
use Infrastructure\Services\Auth\Signature;

class SignatureRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws InvalidSignatureException
     */
    public function passes($attribute, $value): bool
    {
        $signature = $value;
        $url = request()->url();
        $params = request()->except('signature', 'newsSubscription');

        if (!Signature::validate($signature, $url, $params)) {
            throw new InvalidSignatureException;
        }

        $this->invalidateDisposable($signature);

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('validation.signature');
    }

    private function invalidateDisposable(string $signature): void
    {
        $params = Signature::get($signature);

        if ($params['disposable'] === '1') {
            Signature::invalidate($signature);
        }
    }
}
