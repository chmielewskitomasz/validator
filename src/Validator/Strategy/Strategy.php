<?php

declare(strict_types = 1);

namespace Hop\Validator\Strategy;

interface Strategy
{
    /**
     * @return FieldInterface[]
     */
    public function getFields(): array;
}
