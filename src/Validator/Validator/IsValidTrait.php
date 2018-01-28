<?php

declare(strict_types = 1);

namespace Hop\Validator\Validator;

/**
 * Trait IsValidTrait
 * @package Hop\Validator\Validator
 */
trait IsValidTrait
{
    /**
     * @inheritdoc
     */
    public function isValid($value, ?array $options): bool
    {
        return $this->getMessage($value, $options) === null;
    }
}
