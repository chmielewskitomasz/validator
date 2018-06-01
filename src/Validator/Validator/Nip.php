<?php

declare(strict_types = 1);

namespace Hop\Validator\Validator;

class Nip implements RuleValidator
{
    use IsValidTrait;

    /**
     * @inheritdoc
     */
    public function getMessage($value, ?array $options)
    {
        if (!preg_match('/^[a-z]{2}\d{10}$/i', (string)$value)) {
            return 'Pattern does not match polish NIP';
        }

        return null;
    }
}
