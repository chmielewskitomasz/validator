<?php

declare(strict_types = 1);

namespace Test\Filter;

use Hop\Validator\Filter\ArrayKeys;
use Hop\Validator\Filter\RuleFilter;
use PHPUnit\Framework\TestCase;

final class ArrayKeysTest extends TestCase
{
    /**
     * @var ArrayKeys
     */
    private $filter;

    public function setUp()
    {
        $this->filter = new ArrayKeys();
    }

    public function test_instanceOf(): void
    {
        $this->assertInstanceOf(RuleFilter::class, $this->filter);
    }

    public function test_notArray(): void
    {
        $this->assertEquals('someString', $this->filter->filter('someString', []));
    }

    public function test_missingConfig(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->filter->filter([], []);
    }

    public function test_keys(): void
    {
        $array = [
            'a' => 'b',
            'c' => 'd',
            'e' => 'f'
        ];
        $result = $this->filter->filter($array, ['keys' => [
            'a', 'c'
        ]]);
        $this->assertEquals(['a' => 'b', 'c' => 'd'], $result);
    }
}
