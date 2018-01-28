<?php

declare(strict_types = 1);

namespace Test\Strategy;

use Hop\Validator\Strategy\Field;
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

    public function test_instance()
    {
        $this->assertEquals('field', $this->field->fieldName());
        $this->assertTrue($this->field->required());
        $this->assertNull($this->field->condition());
    }

    public function test_validators()
    {
        $this->field->registerValidator('Validator', ['example']);
        $validators = $this->field->validators();
        $this->assertArrayHasKey('Validator', $validators);
        $this->assertCount(1, $validators['Validator']);
    }
}
