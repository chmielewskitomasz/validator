<?php

declare(strict_types = 1);

namespace Test\Validator;

use Hop\Validator\Validator\RuleValidator;
use Hop\Validator\Validator\Uuid;
use PhpCsFixer\Tests\TestCase;

class UuidTest extends TestCase
{
    /**
     * @var Uuid
     */
    private $uuid;

    /**
     * @var array
     */
    private $validUuids = [
        'F8FB983F-F3F8-490E-87E5-106B7EBE5C1E',
        '32d0f0fc-a15a-436d-bbf3-5a5646b55054'
    ];

    private $invalidUuids = [
        '2a4b706f-2586-4223-a773-b154b2ada1bw',
        '2a4b706f-2586-4223-a773-b154b2ada1b',
        '2a4b706-32586-4223-a773-b154b2ada1ba',
    ];
    
    public function setUp()
    {
        $this->uuid = new Uuid();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(RuleValidator::class, $this->uuid);
    }

    public function test_valid()
    {
        foreach ($this->validUuids as $validUuid) {
            $this->assertTrue($this->uuid->isValid($validUuid, null));
            $this->assertNull($this->uuid->getMessage($validUuid, null));
        }
    }

    public function test_invalid()
    {
        foreach ($this->invalidUuids as $invalidUuid) {
            $this->assertFalse($this->uuid->isValid($invalidUuid, null));
            $this->assertNotNull($this->uuid->getMessage($invalidUuid, null));
        }
    }
}
