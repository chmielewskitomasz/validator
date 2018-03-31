<?php

declare(strict_types = 1);

namespace Hop\Validator\Validator;

class Uuid implements RuleValidator
{
    use IsValidTrait;

    /**
     * @inheritdoc
     */
    public function getMessage($value, ?array $options): ?string
    {
        if (!\is_scalar($value)) {
            return 'Param is not scalar';
        }

        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', (string)$value)) {
            return 'Pattern does not match UUID';
        }

        return null;
    }
}
