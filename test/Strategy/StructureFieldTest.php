<?php

declare(strict_types = 1);

namespace Test\Strategy;

use Hop\Validator\Strategy\FieldInterface;
use Hop\Validator\Strategy\Strategy;
use Hop\Validator\Strategy\StructureField;
use PHPUnit\Framework\TestCase;

final class StructureFieldTest extends TestCase
{
    /**
     * @var StructureField
     */
    private $field;

    public function setUp(): void
    {
        $this->field = new StructureField(
            'someNestedField',
            true,
            null,
            false,
            $this->createMock(Strategy::class)
        );
    }

    public function test_instaceOf(): void
    {
        $this->assertInstanceOf(FieldInterface::class, $this->field);
    }

    public function test_config(): void
    {
        $this->assertTrue($this->field->required());
        $this->assertNull($this->field->condition());
        $this->assertFalse($this->field->isArray());
        $this->assertInstanceOf(Strategy::class, $this->field->strategy([]));
    }

    public function test_noStrategyNoConditions(): void
    {
        $field = new StructureField(
            'someNestedField',
            true,
            null,
            false,
            null
        );

        $this->expectException(\RuntimeException::class);
        $field->strategy([]);
    }

    public function test_noStrategyNotApplyingCondition(): void
    {
        $field = new StructureField(
            'someNestedField',
            true,
            null,
            false,
            null
        );

        $field->registerConditionalStrategy(new StructureField\ConditionalStrategy($this->createMock(Strategy::class), function (): bool {
            return false;
        }));

        $field->registerConditionalStrategy(new StructureField\ConditionalStrategy($this->createMock(Strategy::class), function (): bool {
            return false;
        }));

        $this->expectException(\RuntimeException::class);
        $field->strategy([]);
    }

    public function test_noStrategyApplyingCondition(): void
    {
        $field = new StructureField(
            'someNestedField',
            true,
            null,
            false,
            null
        );

        $field->registerConditionalStrategy(new StructureField\ConditionalStrategy($this->createMock(Strategy::class), function (): bool {
            return false;
        }));

        $field->registerConditionalStrategy(new StructureField\ConditionalStrategy($this->createMock(Strategy::class), function (): bool {
            return true;
        }));

        $this->assertInstanceOf(Strategy::class, $field->strategy([]));
    }
}
