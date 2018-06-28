<?php

declare(strict_types = 1);

namespace Hop\Validator\Strategy;

use Hop\Validator\Strategy\StructureField\ConditionalStrategy;

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
     * @var Strategy|null
     */
    private $strategy;

    /**
     * @var ConditionalStrategy[]
     */
    private $conditionalStrategies;

    /**
     * StructureField constructor.
     * @param string $fieldName
     * @param bool $required
     * @param callable|null $condition
     * @param bool $isArray
     * @param Strategy|null $strategy
     */
    public function __construct(
        string $fieldName,
        bool $required,
        ?callable $condition,
        bool $isArray,
        ?Strategy $strategy
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
     * @param ConditionalStrategy $strategy
     */
    public function registerConditionalStrategy(ConditionalStrategy $strategy): void
    {
        if ($this->strategy !== null) {
            throw new \RuntimeException('Main strategy is set already');
        }

        $this->conditionalStrategies[] = $strategy;
    }

    /**
     * @param mixed $data
     * @return Strategy
     */
    public function strategy($data): Strategy
    {
        if ($this->strategy !== null) {
            return $this->strategy;
        }

        if (\count($this->conditionalStrategies) === 0) {
            throw new \RuntimeException('Main strategy is not set and there is no conditional strategies added');
        }

        foreach ($this->conditionalStrategies as $strategy) {
            if ($strategy->shouldBeApplied($data)) {
                return $strategy->strategy();
            }
        }

        throw new \RuntimeException('No strategy has been applied to the data');
    }
}
