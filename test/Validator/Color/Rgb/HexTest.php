<?php

declare(strict_types = 1);

namespace Test\Validator\Color\Rgb;

use Hop\Validator\Validator\Color\Rgb\Hex;
use PHPUnit\Framework\TestCase;

final class HexTest extends TestCase
{
    /**
     * @var Hex
     */
    private $hex;

    private $incorrectRgbHex = [
        '1p1a1a',
        'a2a2a2a',
        'a1a1a',
        'a1'
    ];

    private $correctHex = [
        'a1a1a1',
        'a3a'
    ];

    public function setUp(): void
    {
        $this->hex = new Hex();
    }

    public function test_notHex(): void
    {
        foreach ($this->incorrectRgbHex as $hex) {
            $this->assertFalse($this->hex->isValid($hex, null));
        }
    }

    public function test_hex(): void
    {
        foreach ($this->correctHex as $hex) {
            $this->assertTrue($this->hex->isValid($hex, null));
        }
    }
}
