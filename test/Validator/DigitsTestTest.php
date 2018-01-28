<?php

declare(strict_types = 1);

namespace Test\Validator;

use Hop\Validator\Validator\Digits;
use Hop\Validator\Validator\RuleValidator;
use PHPUnit\Framework\TestCase;

class DigitsTest extends TestCase
{
    /**
     * @var Digits
     */
    private $rule;

    public function setUp()
    {
        $this->rule = new Digits();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(RuleValidator::class, $this->rule);
    }

    public function test_digits()
    {
        $this->assertTrue($this->rule->isValid('634424472', null));
        $this->assertNull($this->rule->getMessage('634424472', null));

        $this->assertTrue($this->rule->isValid(123456, null));
        $this->assertNull($this->rule->getMessage(123456, null));
    }

    public function test_notDigits()
    {
        $this->assertFalse($this->rule->isValid('a634424472', null));
        $this->assertNotNull($this->rule->getMessage('a634424472', null));

        $this->assertFalse($this->rule->isValid('634424472 ', null));
        $this->assertNotNull($this->rule->getMessage('a634424472 ', null));

        $this->assertFalse($this->rule->isValid('634424472a', null));
        $this->assertNotNull($this->rule->getMessage('a634424472a', null));
    }
}
