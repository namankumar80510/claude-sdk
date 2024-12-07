<?php

declare(strict_types=1);

namespace Dikki\Claude\Message;

use Dikki\Claude\Enum\RoleEnum;

class MessageBuilder
{
    private MessageCollection $collection;

    public function __construct()
    {
        $this->collection = new MessageCollection();
    }

    public function system(string $content): self
    {
        $this->collection->add(Message::create(RoleEnum::SYSTEM, $content));
        return $this;
    }

    public function user(string $content, ?array $metadata = null, ?array $attachments = null): self
    {
        $this->collection->add(Message::create(RoleEnum::USER, $content, $metadata, $attachments));
        return $this;
    }

    public function assistant(string $content): self
    {
        $this->collection->add(Message::create(RoleEnum::ASSISTANT, $content));
        return $this;
    }

    public function build(): MessageCollection
    {
        return $this->collection;
    }
} 