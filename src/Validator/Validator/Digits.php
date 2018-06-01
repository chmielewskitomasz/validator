<?php

declare(strict_types = 1);

namespace Hop\Validator\Validator;

class Digits implements RuleValidator
{
    use IsValidTrait;

    /**
     * @inheritdoc
     */
    public function getMessage($value, ?array $options)
    {
        if (!preg_match('/^\d+$/', (string)$value)) {
            return 'Value is not a number';
        }
        return null;
    }
}
