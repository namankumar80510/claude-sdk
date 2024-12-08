<?php

declare(strict_types=1);

namespace Dikki\Claude\Contracts;

use Dikki\Claude\Enum\RoleEnum;

interface MessageInterface extends \JsonSerializable
{
    public function getRole(): RoleEnum;
    public function getContent(): string;
    public function getMetadata(): ?array;
    public function getAttachments(): ?array;
} 