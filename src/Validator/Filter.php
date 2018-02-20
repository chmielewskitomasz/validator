<?php

declare(strict_types = 1);

namespace Hop\Validator;

use Hop\Validator\Strategy\Strategy;

/**
 * Interface Filter
 * @package Hop\Validator
 */
interface Filter
{
    /**
     * @param array $data
     * @param Strategy $strategy
     * @return array
     */
    public function filter(array $data, Strategy $strategy): array;
}
