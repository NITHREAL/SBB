<?php

namespace App\Orchid\Core\Actions;

abstract class ButtonAction extends BaseAction
{
    protected string $method;

    public function __construct(string $method)
    {
        $this->setMethod($method);
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }
}
