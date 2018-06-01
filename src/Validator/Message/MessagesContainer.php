<?php

declare(strict_types = 1);

namespace Hop\Validator\Message;

class MessagesContainer implements MessageInterface, \Countable, \IteratorAggregate
{
    /**
     * @var MessageInterface[]
     */
    private $messages = [];

    /**
     * @param string $index
     * @param MessageInterface $message
     */
    public function attachMessage(string $index, MessageInterface $message): void
    {
        if (isset($this->messages[$index])) {
            $this->messages[$index] = $this->messages[$index]->merge($message);
            return;
        }
        $this->messages[$index] = $message;
    }

    public function count(): int
    {
        $cnt = 0;
        foreach ($this->messages as $message) {
            $cnt+= $message->count();
        }
        return $cnt;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->messages);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this->messages as $index => $message) {
            if ($message->count() === 0) {
                continue;
            }
            $array[$index] = $message->toArray();
        }
        return $array;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @param mixed $index
     * @return MessageInterface|null
     */
    public function findByIndex($index): ?MessageInterface
    {
        if (!isset($this->messages[$index])) {
            return null;
        }
        return $this->messages[$index];
    }

    /**
     * @inheritdoc
     */
    public function merge(MessageInterface $message): MessageInterface
    {
        if (!$message instanceof static) {
            throw new \InvalidArgumentException('Message must be a container');
        }

        $cloned = clone $this;
        foreach ($message->getMessages() as $index => $mergeMessage) {
            $foundMessage = $cloned->findByIndex($index);
            if ($foundMessage === null) {
                $cloned->attachMessage($index, $mergeMessage);
                continue;
            }
            $cloned->attachMessage($index, $foundMessage->merge($mergeMessage));
        }
        return $cloned;
    }
}
