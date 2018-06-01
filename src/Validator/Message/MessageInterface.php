<?php

declare(strict_types = 1);

namespace Hop\Validator\Message;

interface MessageInterface
{
    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param MessageInterface $message
     * @return MessageInterface
     */
    public function merge(MessageInterface $message): MessageInterface;
}
