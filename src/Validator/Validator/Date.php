<?php

declare(strict_types = 1);

namespace Hop\Validator\Validator;

/**
 * Class Date
 * @package Hop\Validator\Validator
 */
final class Date implements RuleValidator
{
    use IsValidTrait;

    /**
     * @param mixed $value
     * @param array|null $options
     * @return null|string
     */
    public function getMessage($value, ?array $options)
    {
        if (!\preg_match('/^\d{4}\-\d{2}\-\d{2}$/', (string)$value)) {
            return 'Value is not a date';
        }
        return null;
    }
}
