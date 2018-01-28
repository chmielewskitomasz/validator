<?php

declare(strict_types = 1);

namespace Test\Validator;

use Hop\Validator\Validator\NotEmpty;
use Hop\Validator\Validator\RuleValidator;
use PHPUnit\Framework\TestCase;

class NotEmptyTest extends TestCase
{
    /**
     * @var NotEmpty
     */
    private $notEmpty;

    public function setUp()
    {
        $this->notEmpty = new NotEmpty();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(RuleValidator::class, $this->notEmpty);
    }

    public function test_empty()
    {
        $this->assertFalse($this->notEmpty->isValid(null, null));
        $this->assertNotNull($this->notEmpty->getMessage(null, null));

        $this->assertFalse($this->notEmpty->isValid('', null));
        $this->assertNotNull($this->notEmpty->getMessage('', null));
    }

    public function test_notEmpty()
    {
        $this->assertTrue($this->notEmpty->isValid(1, null));
        $this->assertNull($this->notEmpty->getMessage(1, null));

        $this->assertTrue($this->notEmpty->isValid('Hi', null));
        $this->assertNull($this->notEmpty->getMessage('Hi', null));
    }
}
