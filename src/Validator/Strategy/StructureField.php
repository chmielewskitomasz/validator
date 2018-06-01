<?php

declare(strict_types = 1);

namespace Hop\Validator\Strategy;

final class StructureField implements FieldInterface
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var bool
     */
    private $required;

    /**
     * @var callable|null
     */
    private $condition;

    /**
     * @var bool
     */
    private $isArray;

    /**
     * @var Strategy
     */
    private $strategy;

    /**
     * StructureField constructor.
     * @param string $fieldName
     * @param bool $required
     * @param callable|null $condition
     * @param bool $isArray
     * @param Strategy $strategy
     */
    public function __construct(
        string $fieldName,
        bool $required,
        ?callable $condition,
        bool $isArray,
        Strategy $strategy
    ) {
        $this->fieldName = $fieldName;
        $this->required = $required;
        $this->condition = $condition;
        $this->isArray = $isArray;
        $this->strategy = $strategy;
    }

    /**
     * @inheritdoc
     */
    public function fieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @inheritdoc
     */
    public function required(): bool
    {
        return $this->required;
    }

    /**
     * @inheritdoc
     */
    public function condition(): ?callable
    {
        return $this->condition;
    }

    /**
     * @inheritdoc
     */
    public function isArray(): bool
    {
        return $this->isArray;
    }

    /**
     * @return Strategy
     */
    public function strategy(): Strategy
    {
        return $this->strategy;
    }
}
