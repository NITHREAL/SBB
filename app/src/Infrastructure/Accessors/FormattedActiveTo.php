<?php

namespace Infrastructure\Accessors;

final readonly class FormattedActiveTo
{
    public function __construct(
        private object $promoAction,
    ) {
    }

    public function __invoke(): ?string
    {
        $activeTo = $this->promoAction->active_to;

        return $activeTo
            ? $activeTo->format('d-m-Y')
            : null;
    }
}
