<?php

namespace Domain\Exchange\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

abstract class ItemRequest extends FormRequest
{
    private bool $valid = false;

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function validate(): bool
    {
        try {
            $this->validateResolved();
            $this->valid = true;
        } catch (ValidationException $ex) {
            $this->valid = false;
        }

        return $this->valid;
    }

    public function getErrorMessages(): array
    {
        $validator = $this->getValidatorInstance();

        return $validator->errors()->messages();
    }
}
