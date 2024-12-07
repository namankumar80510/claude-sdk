<?php

declare(strict_types=1);

namespace Dikki\Claude\Message;

use Dikki\Claude\Exception\ValidationException;

class MessageCollection implements \Countable, \IteratorAggregate
{
    private array $messages = [];

    public function add(Message $message): self
    {
        $this->messages[] = $message;
        return $this;
    }

    public function addMany(array $messages): self
    {
        foreach ($messages as $message) {
            if (!$message instanceof Message) {
                throw new ValidationException('All messages must be instances of Message class');
            }
            $this->add($message);
        }
        return $this;
    }

    public function clear(): void
    {
        $this->messages = [];
    }

    public function count(): int
    {
        return count($this->messages);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->messages);
    }

    public function toArray(): array
    {
        return array_map(fn(Message $message) => $message->jsonSerialize(), $this->messages);
    }
} 