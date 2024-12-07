<?php

declare(strict_types=1);

namespace Dikki\Claude\Response;

use Dikki\Claude\Contracts\ResponseInterface;

class Response implements ResponseInterface
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId(): string
    {
        return $this->data['id'];
    }

    public function getContent(): string
    {
        return $this->data['content'][0]['text'];
    }

    public function getModel(): string
    {
        return $this->data['model'];
    }

    public function getRole(): string
    {
        return $this->data['role'];
    }

    public function getStopReason(): ?string
    {
        return $this->data['stop_reason'] ?? null;
    }

    public function getStopSequence(): ?string
    {
        return $this->data['stop_sequence'] ?? null;
    }

    public function getUsage(): array
    {
        return $this->data['usage'] ?? [];
    }

    public function getRaw(): array
    {
        return $this->data;
    }
} 