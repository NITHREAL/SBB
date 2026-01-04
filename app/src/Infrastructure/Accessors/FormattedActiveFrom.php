<?php

namespace Infrastructure\Accessors;

final readonly class FormattedActiveFrom
{
    public function __construct(
        private object $promoAction,
    ) {
    }

    public function __invoke(): ?string
    {
        $activeFrom = $this->promoAction->active_from;

        return $activeFrom
            ? $activeFrom->format('d-m-Y')
            : null;
    }
}
