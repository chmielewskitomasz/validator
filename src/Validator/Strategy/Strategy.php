<?php

declare(strict_types = 1);

namespace Hop\Validator\Strategy;

interface Strategy
{
    /**
     * @return Field[]
     */
    public function getFields(): array;
}
