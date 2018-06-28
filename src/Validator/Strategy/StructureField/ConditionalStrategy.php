<?php

declare(strict_types = 1);

namespace Hop\Validator\Strategy\StructureField;

use Hop\Validator\Strategy\Strategy;

/**
 * Class ConditionalStrategy
 * @package Hop\Validator\Strategy\StructureField
 */
final class ConditionalStrategy
{
    /**
     * @var Strategy
     */
    private $strategy;

    /**
     * @var callable
     */
    private $condition;

    /**
     * ConditionalStrategy constructor.
     * @param Strategy $strategy
     * @param callable $condition
     */
    public function __construct(
        Strategy $strategy,
        callable $condition
    ) {
        $this->strategy = $strategy;
        $this->condition = $condition;
    }

    /**
     * @return Strategy
     */
    public function strategy(): Strategy
    {
        return $this->strategy;
    }


    /**
     * @param mixed $data
     * @return bool
     */
    public function shouldBeApplied($data): bool
    {
        return ($this->condition)($data) === true;
    }
}
