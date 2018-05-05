<?php

declare(strict_types = 1);

namespace Test\Validator;

use Hop\Validator\Validator\Range;
use Hop\Validator\Validator\RuleValidator;
use PHPUnit\Framework\TestCase;

class RangeTest extends TestCase
{
    /**
     * @var Range
     */
    private $length;

    public function setUp()
    {
        $this->length = new Range();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(RuleValidator::class, $this->length);
    }

    public function test_notScalar()
    {
        $this->assertFalse($this->length->isValid(new \stdClass(), null));
    }

    public function test_lackOptions()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->length->isValid(3, null);

        $this->expectException(\InvalidArgumentException::class);
        $this->length->getMessage(3, null);

        $this->expectException(\InvalidArgumentException::class);
        $this->length->isValid(3, []);

        $this->expectException(\InvalidArgumentException::class);
        $this->length->getMessage(3, []);
    }

    public function test_minGreaterThanMax()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->length->isValid(3, [
            'min' => 4,
            'max' => 3
        ]);

        $this->expectException(\InvalidArgumentException::class);
        $this->length->getMessage(3, [
            'min' => 4,
            'max' => 3
        ]);
    }

    public function test_valid()
    {
        $this->assertTrue($this->length->isValid(3, [
            'min' => 2,
            'max' => 4
        ]));

        $this->assertNull($this->length->getMessage(3, [
            'min' => 2,
            'max' => 4
        ]));

        $this->assertTrue($this->length->isValid(3, [
            'min' => 3,
            'max' => 3
        ]));

        $this->assertNull($this->length->getMessage(3, [
            'min' => 3,
            'max' => 3
        ]));

        $this->assertTrue($this->length->isValid(3.3, [
            'min' => 3.1,
            'max' => 3.4
        ]));

        $this->assertNull($this->length->getMessage(3.3, [
            'min' => 3.1,
            'max' => 3.4
        ]));
    }

    public function test_tooLow()
    {
        $this->assertFalse($this->length->isValid(3, [
            'min' => 4
        ]));

        $this->assertNotNull($this->length->getMessage(3, [
            'min' => 4
        ]));
    }

    public function test_tooHigh()
    {
        $this->assertFalse($this->length->isValid(5, [
            'max' => 4
        ]));

        $this->assertNotNull($this->length->getMessage(5, [
            'max' => 4
        ]));
    }
}
