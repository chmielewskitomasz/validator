<?php

declare(strict_types = 1);

namespace Hop\Validator\Filter;

/**
 * Class ToLower
 * @package Hop\Validator\Filter
 */
final class ToLower implements RuleFilter
{
    /**
     * @param mixed $value
     * @param array|null $options
     * @return mixed|string
     */
    public function filter($value, ?array $options)
    {
        if (!\is_string($value)) {
            return $value;
        }

        return \strtolower($value);
    }
}
