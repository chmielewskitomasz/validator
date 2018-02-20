<?php

declare(strict_types = 1);

namespace Hop\Validator\Strategy;

class Field
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
     */
    public function registerValidator(string $name, ?array $options): void
    {
        $this->validators[$name] = $options;
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
     */
    public function registerFilter(string $name, ?array $options): void
    {
        $this->filters[$name] = $options;
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return $this->filters;
    }
}
