<?php

declare(strict_types=1);

namespace Dikki\Claude\Validator;

use Dikki\Claude\Exception\ValidationException;
use Dikki\Claude\Message\MessageCollection;

class RequestValidator
{
    public function validate(MessageCollection $messages, array $options = []): void
    {
        $this->validateMessages($messages);
        $this->validateOptions($options);
    }

    private function validateMessages(MessageCollection $messages): void
    {
        if (count($messages) === 0) {
            throw new ValidationException('Message collection cannot be empty');
        }

        if (count($messages) > 4) {
            throw new ValidationException('Maximum of 4 messages allowed per request');
        }
    }

    private function validateOptions(array $options): void
    {
        if (isset($options['temperature'])) {
            if ($options['temperature'] < 0 || $options['temperature'] > 1) {
                throw new ValidationException('Temperature must be between 0 and 1');
            }
        }

        if (isset($options['max_tokens'])) {
            if ($options['max_tokens'] < 1) {
                throw new ValidationException('max_tokens must be greater than 0');
            }
        }

        if (isset($options['top_p'])) {
            if ($options['top_p'] < 0 || $options['top_p'] > 1) {
                throw new ValidationException('top_p must be between 0 and 1');
            }
        }
    }
} 