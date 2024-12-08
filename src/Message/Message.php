<?php

declare(strict_types=1);

namespace Dikki\Claude\Message;

use Dikki\Claude\Enum\RoleEnum;
use Dikki\Claude\Exception\ValidationException;

class Message implements \JsonSerializable
{
    private function __construct(
        private readonly RoleEnum $role,
        private readonly string $content,
        private readonly ?array $metadata = null,
        private readonly ?array $attachments = null
    ) {}

    public static function create(
        RoleEnum $role,
        string $content,
        ?array $metadata = null,
        ?array $attachments = null
    ): self {
        if (empty(trim($content))) {
            throw new ValidationException('Message content cannot be empty');
        }

        return new self($role, $content, $metadata, $attachments);
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'role' => $this->role->value,
            'content' => $this->content,
            'metadata' => $this->metadata,
            'attachments' => $this->attachments
        ]);
    }
} 