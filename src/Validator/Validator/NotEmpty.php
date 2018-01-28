<?php

declare(strict_types = 1);

namespace Hop\Validator\Validator;

class NotEmpty implements RuleValidator
{
    use IsValidTrait;

    public function getMessage($value, ?array $options): ?string
    {
        if ((string)$value === '') {
            return 'Value must not be empty';
        }
        return null;
    }
}
