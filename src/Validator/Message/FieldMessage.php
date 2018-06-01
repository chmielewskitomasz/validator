<?php

declare(strict_types = 1);

namespace Hop\Validator\Message;

final class FieldMessage implements MessageInterface
{
    /**
     * @var string[]
     */
    private $messages = [];

    /**
     * @param string $code
     * @param string $name
     * @return FieldMessage
     */
    public function attachMessage(string $code, string $name): self
    {
        $this->messages[$code] = $name;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->messages;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->messages);
    }

    public function merge(MessageInterface $message): MessageInterface
    {
        $messageContainer = clone $this;
        if (!$message instanceof static) {
            throw new \InvalidArgumentException('Message must be a field message');
        }
        foreach ($message->toArray() as $error => $text) {
            $messageContainer->attachMessage($error, $text);
        }
        return $messageContainer;
    }
}
