<?php

declare(strict_types = 1);

namespace Hop\Validator\Validator\Color\Rgb;

use Hop\Validator\Validator\IsValidTrait;
use Hop\Validator\Validator\RuleValidator;

final class Hex implements RuleValidator
{
    use IsValidTrait;

    public function getMessage($value, ?array $options)
    {
        if (!preg_match('/^([a-fA-F0-9]{3}){1,2}$/', (string)$value)) {
            return 'Value is not a number';
        }
        return null;
    }
}
