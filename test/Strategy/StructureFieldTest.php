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
        $this->assertInstanceOf(Strategy::class, $this->field->strategy());
    }
}
