<?php

declare(strict_types = 1);

namespace Test\Validator;

use Hop\Validator\Validator\Nip;
use Hop\Validator\Validator\RuleValidator;
use PHPUnit\Framework\TestCase;

class NipTest extends TestCase
{
    /**
     * @var Nip
     */
    private $nip;

    /**
     * @var array
     */
    private $validNips = [
        'pl5334332213',
        'PL5334332210'
    ];

    private $invalidNips = [
        '0123456789',
        'PL123456789',
    ];

    public function setUp()
    {
        $this->nip = new Nip();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(RuleValidator::class, $this->nip);
    }

    public function test_valid()
    {
        foreach ($this->validNips as $validNip) {
            $this->assertTrue($this->nip->isValid($validNip, null));
            $this->assertNull($this->nip->getMessage($validNip, null));
        }
    }

    public function test_invalid()
    {
        foreach ($this->invalidNips as $invalidNip) {
            $this->assertFalse($this->nip->isValid($invalidNip, null));
            $this->assertNotNull($this->nip->getMessage($invalidNip, null));
        }
    }
}
