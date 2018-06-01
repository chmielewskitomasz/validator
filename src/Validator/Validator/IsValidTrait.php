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

    /**
     * @param mixed $value
     * @param array|null $options
     * @return mixed
     */
    abstract public function getMessage($value, ?array $options);
}
