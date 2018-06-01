<?php

declare(strict_types = 1);

namespace Hop\Validator\Strategy;

class Field implements FieldInterface
{
    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var array
     */
    private $validators = [];

    /**
     * @var array
     */
    private $filters = [];

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
    private $isArray = false;

    /**
     * Field constructor.
     * @param string $fieldName
     * @param bool $required
     * @param callable|null $condition
     */
    public function __construct(
        string $fieldName,
        bool $required,
        ?callable $condition
    ) {
        $this->fieldName = $fieldName;
        $this->required = $required;
        $this->condition = $condition;
    }

    /**
     * @param string $name
     * @param array|null $options
     * @return Field
     */
    public function registerValidator(string $name, ?array $options): self
    {
        $this->validators[$name] = $options;
        return $this;
    }

    /**
     * @param bool $isArray
     * @return Field
     */
    public function setIsArray(bool $isArray): self
    {
        $this->isArray = $isArray;
        return $this;
    }

    /**
     * @return string
     */
    public function fieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @return bool
     */
    public function required(): bool
    {
        return $this->required;
    }

    /**
     * @return array
     */
    public function validators(): array
    {
        return $this->validators;
    }

    /**
     * @return callable|null
     */
    public function condition(): ?callable
    {
        return $this->condition;
    }

    /**
     * @param string $name
     * @param array|null $options
     * @return Field
     */
    public function registerFilter(string $name, ?array $options): self
    {
        $this->filters[$name] = $options;
        return $this;
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return $this->filters;
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->isArray;
    }
}
