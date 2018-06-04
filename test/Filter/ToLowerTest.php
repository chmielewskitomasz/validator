<?php

declare(strict_types = 1);

namespace Test\Filter;

use Hop\Validator\Filter\RuleFilter;
use Hop\Validator\Filter\ToLower;
use PHPUnit\Framework\TestCase;

final class ToLowerTest extends TestCase
{
    /**
     * @var ToLower
     */
    private $filter;

    public function setUp()
    {
        $this->filter = new ToLower();
    }

    public function test_instanceOf(): void
    {
        $this->assertInstanceOf(RuleFilter::class, $this->filter);
    }

    public function test_noString(): void
    {
        $this->assertEquals(0, $this->filter->filter(0, null));
    }

    public function test_string(): void
    {
        $this->assertEquals('abcdefghij', $this->filter->filter('AbCdEfGhIj', null));
    }
}
