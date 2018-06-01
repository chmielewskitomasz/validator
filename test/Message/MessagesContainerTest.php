<?php

declare(strict_types = 1);

namespace Test\Message;

use Hop\Validator\Message\FieldMessage;
use Hop\Validator\Message\MessageInterface;
use Hop\Validator\Message\MessagesContainer;
use PHPUnit\Framework\TestCase;

final class MessagesContainerTest extends TestCase
{
    /**
     * @var MessagesContainer
     */
    private $messagesContainer;

    public function setUp(): void
    {
        $this->messagesContainer = new MessagesContainer();
    }

    public function test_instanceOf(): void
    {
        $this->assertInstanceOf(MessageInterface::class, $this->messagesContainer);
    }

    public function test_attachMessage(): void
    {
        $this->assertEquals([], $this->messagesContainer->toArray());

        $messagesContainer = new MessagesContainer();
        $messagesContainer->attachMessage('innerIndex', new MessagesContainer());
        $this->assertEquals([], $messagesContainer->toArray());
        $this->messagesContainer->attachMessage('index1', $messagesContainer);
        $this->assertEquals([], $this->messagesContainer->toArray());

        $messagesContainerStubIndex = new MessagesContainer();
        $messagesContainerStubIndex->attachMessage('error', (new FieldMessage())->attachMessage('error', 'Error'));
        $messagesContainerStub = new MessagesContainer();
        $messagesContainerStub->attachMessage('innerIndex', $messagesContainerStubIndex);

        $this->messagesContainer->attachMessage('index1', $messagesContainerStub);

        $this->assertEquals(['index1' => ['innerIndex' => ['error' => ['error' => 'Error']]]], $this->messagesContainer->toArray());

        $this->messagesContainer->attachMessage('newIndex', (new FieldMessage())->attachMessage('test', 'Test'));
        $this->assertEquals(['index1' => ['innerIndex' => ['error' => ['error' => 'Error']]], 'newIndex' => ['test' => 'Test']], $this->messagesContainer->toArray());
    }

    public function test_count(): void
    {
        $this->assertEquals(0, $this->messagesContainer->count());
        $messagesContainer = new MessagesContainer();
        $this->messagesContainer->attachMessage('inner', $messagesContainer);
        $this->assertEquals(0, $this->messagesContainer->count());

        $stub = $this->createMock(MessageInterface::class);
        $stub->method('count')
            ->willReturn(2);

        $messagesContainer->attachMessage('next', $stub);
        $this->assertEquals(2, $this->messagesContainer->count());

        $stub = $this->createMock(MessageInterface::class);
        $stub->method('count')
            ->willReturn(3);

        $messagesContainer = new MessagesContainer();
        $messagesContainer->attachMessage('next1', $stub);
        $this->messagesContainer->attachMessage('next2', $messagesContainer);

        $this->assertEquals(5, $this->messagesContainer->count());
    }

    public function test_merge(): void
    {
        $container = new MessagesContainer();
        $anotherContainer = new MessagesContainer();
        $container->attachMessage('anotherContainer', $anotherContainer);

        $this->assertEquals([], $container->toArray());

        $anotherContainer->attachMessage('fieldErrors', (new FieldMessage())->attachMessage('error1', 'Error1'));

        $this->assertEquals(['anotherContainer' => ['fieldErrors' => ['error1' => 'Error1']]], $container->toArray());

        $anotherContainer1 = new MessagesContainer();
        $anotherContainer1->attachMessage('fieldErrors', (new FieldMessage())->attachMessage('error2', 'Error2'));

        $mergeContainer = new MessagesContainer();
        $mergeContainer->attachMessage('anotherContainer', $anotherContainer1);

        $this->assertEquals(['anotherContainer' => ['fieldErrors' => ['error1' => 'Error1', 'error2' => 'Error2']]], $container->merge($mergeContainer)->toArray());
    }

    public function test_mergeInvalidClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->messagesContainer->merge($this->createMock(MessageInterface::class));
    }
}
