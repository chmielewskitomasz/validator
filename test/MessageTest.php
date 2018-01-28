<?php

declare(strict_types = 1);

namespace Test;

use Hop\Validator\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function test_instance()
    {
        $message = new Message('type', 'message');
        $this->assertEquals('type', $message->type());
        $this->assertEquals('message', $message->message());
    }
}
