<?php

declare(strict_types = 1);

namespace Test\Validator;

use Hop\Validator\Validator\Date;
use Hop\Validator\Validator\RuleValidator;
use PHPUnit\Framework\TestCase;

final class DateTest extends TestCase
{
    /**
     * @var Date
     */
    private $rule;

    public function setUp(): void
    {
        $this->rule = new Date();
    }

    public function test_instanceOf(): void
    {
        $this->assertInstanceOf(RuleValidator::class, $this->rule);
    }

    public function test_dateTime(): void
    {
        $this->assertTrue($this->rule->isValid('2018-05-01', null));
    }

    public function test_invalidDateTime(): void
    {
        $this->assertFalse($this->rule->isValid('2018-05-01 00:00:00', null));
        $this->assertFalse($this->rule->isValid('01-05-2018', null));
        $this->assertFalse($this->rule->isValid('2018-05-0', null));
    }
}
