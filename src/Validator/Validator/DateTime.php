<?php

declare(strict_types = 1);

namespace Hop\Validator\Validator;

class DateTime implements RuleValidator
{
    use IsValidTrait;

    /**
     * @inheritdoc
     */
    public function getMessage($value, ?array $options): ?string
    {
        if (!\preg_match('/^\d{4}\-\d{2}\-\d{2} \d{2}\:\d{2}\:\d{2}$/', (string)$value)) {
            return 'Value is not a datetime';
        }
        return null;
    }
}
