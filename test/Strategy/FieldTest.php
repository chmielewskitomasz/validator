<?php

declare(strict_types = 1);

namespace Test\Strategy;

use Hop\Validator\Strategy\Field;
use Hop\Validator\Strategy\FieldInterface;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    /**
     * @var Field
     */
    private $field;

    public function setUp()
    {
        $this->field = new Field(
            'field',
            true,
            null
        );
    }

    public function test_instanceOf(): void
    {
        $this->assertInstanceOf(FieldInterface::class, $this->field);
    }

    public function test_config(): void
    {
        $this->assertEquals('field', $this->field->fieldName());
        $this->assertTrue($this->field->required());
        $this->assertNull($this->field->condition());
    }

    public function test_assertIsArray(): void
    {
        $this->assertFalse($this->field->isArray());
        $this->field->setIsArray(true);
        $this->assertTrue($this->field->isArray());
        $this->field->setIsArray(false);
        $this->assertFalse($this->field->isArray());
    }

    public function test_validators(): void
    {
        $this->field->registerValidator('Validator', ['example']);
        $validators = $this->field->validators();
        $this->assertArrayHasKey('Validator', $validators);
        $this->assertCount(1, $validators['Validator']);
    }

    public function test_filters(): void
    {
        $this->assertCount(0, $this->field->filters());
        $this->field->registerFilter('filter', []);
        $this->assertEquals(['filter' => []], $this->field->filters());
        $this->field->registerFilter('nextFilter', ['someOption' => true]);
        $this->assertEquals(['filter' => [], 'nextFilter' => ['someOption' => true]], $this->field->filters());
    }
}
