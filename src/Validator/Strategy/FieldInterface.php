<?php

declare(strict_types = 1);

namespace Hop\Validator\Strategy;

/**
 * Interface FieldInterface
 * @package Hop\Validator\Strategy
 */
interface FieldInterface
{
    /**
     * @return string
     */
    public function fieldName(): string;

    /**
     * @return bool
     */
    public function required(): bool;

    /**
     * @return callable|null
     */
    public function condition(): ?callable;

    /**
     * @return bool
     */
    public function isArray(): bool;
}
