<?php

declare(strict_types = 1);

namespace Test\Message;

use Hop\Validator\Message\FieldMessage;
use Hop\Validator\Message\MessageInterface;
use Hop\Validator\Message\MessagesContainer;
use PHPUnit\Framework\TestCase;

final class FieldMessageTest extends TestCase
{
    /**
     * @var FieldMessage
     */
    private $fieldMessage;

    public function setUp(): void
    {
        $this->fieldMessage = new FieldMessage();
    }

    public function test_instanceOf(): void
    {
        $this->assertInstanceOf(MessageInterface::class, $this->fieldMessage);
    }

    public function test_attachMessages(): void
    {
        $this->assertEquals([], $this->fieldMessage->toArray());
        $this->fieldMessage->attachMessage('test', 'test message');
        $this->assertEquals(['test' => 'test message'], $this->fieldMessage->toArray());
        $this->fieldMessage->attachMessage('anotherTest', 'Another test message');
        $this->assertEquals(['test' => 'test message', 'anotherTest' => 'Another test message'], $this->fieldMessage->toArray());
    }

    public function test_count(): void
    {
        $this->assertEquals(0, $this->fieldMessage->count());
        $this->fieldMessage->attachMessage('test', 'test message');
        $this->assertEquals(1, $this->fieldMessage->count());
        $this->fieldMessage->attachMessage('anotherTest', 'Another test message');
        $this->assertEquals(2, $this->fieldMessage->count());
    }

    public function test_merge(): void
    {
        $message = new FieldMessage();
        $message->attachMessage('anotherTest', 'Another test message');
        $this->fieldMessage->attachMessage('test', 'test message');
        $this->assertEquals(['test' => 'test message', 'anotherTest' => 'Another test message'], $this->fieldMessage->merge($message)->toArray());
    }

    public function test_mergeWrongClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->fieldMessage->merge(new MessagesContainer());
    }
}
