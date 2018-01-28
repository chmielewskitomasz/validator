<?php

declare(strict_types = 1);

namespace Hop\Validator;

class Messages implements \Countable, \IteratorAggregate
{
    private $messages = [];

    public function getIterator()
    {
        return new \ArrayIterator($this->messages);
    }

    public function count()
    {
        return \count($this->messages);
    }

    public function attachMessage(string $fieldName, Message $message): void
    {
        if (!array_key_exists($fieldName, $this->messages)) {
            $this->messages[$fieldName] = [];
        }

        $this->messages[$fieldName][] = $message;
    }

    public function getMessagesForField(string $field): ?array
    {
        if (!isset($this->messages[$field])) {
            return null;
        }
        return $this->messages[$field];
    }

    public function toArray(): array
    {
        $arr = \array_map(function (array $messages) {
            $rows = [];
            /** @var Message $message */
            foreach ($messages as $message) {
                $rows[$message->type()] = $message->message();
            }
            return $rows;
        }, $this->messages);
        return $arr;
    }
}
