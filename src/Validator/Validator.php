<?php

declare(strict_types = 1);

namespace Hop\Validator;

use Hop\Validator\Strategy\Strategy;

interface Validator
{
    /**
     * @param array $data
     * @param Strategy $strategy
     * @return bool
     */
    public function isValid(array $data, Strategy $strategy): bool;

    /**
     * @param array $data
     * @param Strategy $strategy
     * @return Message[]
     */
    public function getMessages(array $data, Strategy $strategy): array;
}
