<?php

declare(strict_types = 1);

namespace Test\Validator;

use Hop\Validator\Validator\Email;
use Hop\Validator\Validator\RuleValidator;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @var Email
     */
    private $email;

    /**
     * @var array
     */
    private $validAddresses = [
        'tom@tom.pl',
        'tom@tom.ventures'
    ];

    /**
     * @var array
     */
    private $invalidAddresses = [
        'tom',
        'tom@tom@tom',
        'tom@tom'
    ];

    public function setUp(): void
    {
        $this->email = new Email();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(RuleValidator::class, $this->email);
    }

    public function test_email()
    {
        foreach ($this->validAddresses as $validAddress) {
            $this->assertTrue($this->email->isValid($validAddress, null));
            $this->assertNull($this->email->getMessage($validAddress, null));
        }
    }

    public function test_notEmail()
    {
        foreach ($this->invalidAddresses as $invalidAddress) {
            $this->assertFalse($this->email->isValid($invalidAddress, null));
            $this->assertNotNull($this->email->getMessage($invalidAddress, null));
        }
    }

    public function test_invalidValue(): void
    {
        $this->assertFalse($this->email->isValid(true, null));
    }

    public function test_notString(): void
    {
        $this->assertFalse($this->email->isValid(123, null));
    }
}
