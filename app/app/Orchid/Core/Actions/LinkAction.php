<?php

namespace App\Orchid\Core\Actions;

abstract class LinkAction extends BaseAction
{
    protected string $route;
    protected array $parameters;

    public function __construct(string $route, array $parameters = [])
    {
        $this->setRoute($route);
        $this->setParameters($parameters);
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }
}
