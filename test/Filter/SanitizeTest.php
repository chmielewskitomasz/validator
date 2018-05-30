<?php

declare(strict_types = 1);

namespace Test\Filter;

use Hop\Validator\Filter\RuleFilter;
use Hop\Validator\Filter\Sanitize;
use PHPUnit\Framework\TestCase;

class SanitizeTest extends TestCase
{
    /**
     * @var Sanitize
     */
    private $filter;

    public function setUp()
    {
        $this->filter = new Sanitize();
    }

    public function test_instanceOf()
    {
        $this->assertInstanceOf(RuleFilter::class, $this->filter);
    }

    public function test_sanitize()
    {
        $string = '<tag>Tag</tag>"';
        $this->assertEquals('Tag"', $this->filter->filter($string, null));
    }

    public function test_receiveBoolIfBool(): void
    {
        $this->assertEquals(true, $this->filter->filter(true, null));
        $this->assertEquals(false, $this->filter->filter(false, null));
    }
}
