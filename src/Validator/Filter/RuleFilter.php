<?php

declare(strict_types = 1);

namespace Hop\Validator\Filter;

/**
 * Interface RuleFilter
 * @package Hop\Validator\Filter
 */
interface RuleFilter
{
    /**
     * @param mixed $value
     * @param array|null $options
     * @return mixed
     */
    public function filter($value, ?array $options);
}
