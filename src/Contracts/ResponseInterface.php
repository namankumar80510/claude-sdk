<?php

declare(strict_types=1);

namespace Dikki\Claude\Contracts;

interface ResponseInterface
{
    public function getId(): string;
    public function getContent(): string;
    public function getModel(): string;
    public function getRole(): string;
    public function getStopReason(): ?string;
    public function getStopSequence(): ?string;
    public function getUsage(): array;
    public function getRaw(): array;
} 