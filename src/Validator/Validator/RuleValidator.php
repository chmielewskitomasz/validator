<?php

declare(strict_types = 1);

namespace Hop\Validator\Validator;

interface RuleValidator
{
    /**
     * @param $value
     * @param array|null $options
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isValid($value, ?array $options): bool;

    /**
     * @param $value
     * @param array|null $options
     * @return string|null
     * @throws \InvalidArgumentException
     */
    public function getMessage($value, ?array $options): ?string;
}
