<?php

declare(strict_types = 1);

namespace Hop\Validator\Validator;

class InArray implements RuleValidator
{
    use IsValidTrait;

    public function getMessage($value, ?array $options)
    {
        if ($options === null || !isset($options['haystack'])) {
            throw new \InvalidArgumentException('Haystack parameter must be passed in options');
        }

        if (\count($options['haystack']) === 0) {
            throw new \InvalidArgumentException('Haystack parameter must not be an empty array');
        }

        if (!\in_array((string)$value, $options['haystack'], true)) {
            return sprintf('Option \'%s\' not found in passed haystack', $value);
        }

        return null;
    }
}
