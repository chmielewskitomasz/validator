<?php

declare(strict_types = 1);

namespace Hop\Validator\Filter;

final class ArrayKeys implements RuleFilter
{
    public function filter($value, ?array $options)
    {
        if (!\is_array($value)) {
            return $value;
        }

        if (!\array_key_exists('keys', $options)) {
            throw new \InvalidArgumentException('Keys not passed');
        }

        return \array_filter($value, function ($key) use ($options): bool {
            return \in_array($key, $options['keys'], true);
        }, \ARRAY_FILTER_USE_KEY);
    }
}
