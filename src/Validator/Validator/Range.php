<?php

declare(strict_types = 1);

namespace Hop\Validator\Validator;

class Range implements RuleValidator
{
    use IsValidTrait;

    /**
     * @inheritdoc
     */
    public function getMessage($value, ?array $options): ?string
    {
        if (!is_scalar($value)) {
            throw new \InvalidArgumentException('Param must be a scalar value');
        }

        if ($options === null || (!isset($options['min']) && !isset($options['max']))) {
            throw new \InvalidArgumentException('Options must include at least one, min or max param');
        }

        if (isset($options['max'], $options['min']) && (float)$options['max'] < (float)$options['min']) {
            throw new \InvalidArgumentException('max param must be greater than min param');
        }

        if (isset($options['min']) && (float)$value < (float)$options['min']) {
            return sprintf('The minimum value is %s', (float)$options['min']);
        }

        if (isset($options['max']) && (float)$value > (float)$options['max']) {
            return sprintf('The maximum value is %s', (float)$options['max']);
        }

        return null;
    }
}
