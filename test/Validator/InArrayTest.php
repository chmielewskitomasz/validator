<?php

declare(strict_types = 1);

namespace Test\Validator;

use Hop\Validator\Validator\InArray;
use Hop\Validator\Validator\RuleValidator;
use PhpCsFixer\Tests\TestCase;

class InArrayTest extends TestCase
{
    /**
     * @var InArray
     */
    private $inArray;

    public function setUp()
    {
        $this->inArray = new InArray();
    }

    public function test_instanceOf(): void
    {
        $this->assertInstanceOf(RuleValidator::class, $this->inArray);
    }

    public function test_lackOfOption(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->inArray->isValid('test', null);

        $this->expectException(\InvalidArgumentException::class);
        $this->inArray->getMessage('test', null);

        $this->expectException(\InvalidArgumentException::class);
        $this->inArray->isValid('test', []);

        $this->expectException(\InvalidArgumentException::class);
        $this->inArray->getMessage('test', []);
    }

    public function test_invalid(): void
    {
        $this->assertNotNull($this->inArray->getMessage('test', ['haystack' => ['one', 'two']]));
        $this->assertFalse($this->inArray->isValid('test', ['haystack' => ['one', 'two']]));
    }

    public function test_valid(): void
    {
        $this->assertNull($this->inArray->getMessage('one', ['haystack' => ['one', 'two']]));
        $this->assertTrue($this->inArray->isValid('one', ['haystack' => ['one', 'two']]));
    }
}
