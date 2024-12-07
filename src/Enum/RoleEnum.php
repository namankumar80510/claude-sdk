<?php

declare(strict_types=1);

namespace Dikki\Claude\Enum;

enum RoleEnum: string
{
    case USER = 'user';
    case ASSISTANT = 'assistant';
    case SYSTEM = 'system';
} 