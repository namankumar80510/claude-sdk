<?php

declare(strict_types=1);

namespace Dikki\Claude\Enum;

enum ModelEnum: string
{
    case CLAUDE_3_OPUS = 'claude-3-opus-20240229';
    case CLAUDE_3_5_SONNET = 'claude-3-5-sonnet-20241022';
    case CLAUDE_3_SONNET = 'claude-3-sonnet-20240229';
    case CLAUDE_3_5_HAIKU = 'claude-3-5-haiku-20241022';
    case CLAUDE_3_HAIKU = 'claude-3-haiku-20240307';
    case CLAUDE_2_1 = 'claude-2.1';
    
    public static function getDefault(): self
    {
        return self::CLAUDE_2_1;
    }
    
    public static function isValid(string $model): bool
    {
        return in_array($model, array_column(self::cases(), 'value'));
    }
} 