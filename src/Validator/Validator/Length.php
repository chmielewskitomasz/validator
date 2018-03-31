<?php

declare(strict_types = 1);

namespace Hop\Validator\Validator;

class Length implements RuleValidator
{
    use IsValidTrait;

    public function getMessage($value, ?array $options): ?string
    {
        if (!\is_scalar($value)) {
            return 'Param must be a scalar value';
        }

        if ($options === null || (!isset($options['min']) && !isset($options['max']))) {
            throw new \InvalidArgumentException('Options must include at least one, min or max param');
        }

        if (isset($options['max'], $options['min']) && (int)$options['max'] < (int)$options['min']) {
            throw new \InvalidArgumentException('max param must be greater than min param');
        }

        if (isset($options['min']) && \strlen((string)$value) < (int)$options['min']) {
            return sprintf('The minimum length is %s', (int)$options['min']);
        }

        if (isset($options['max']) && \strlen((string)$value) > (int)$options['max']) {
            return sprintf('The maximum value is %s', (int)$options['max']);
        }

        return null;
    }
}
