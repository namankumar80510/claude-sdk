# Claude API PHP SDK

![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue)

![Example Screenshot](https://claude.yojanamagazine.online/images/example.jpg)

This is a useful PHP library that you can use to communicate with Anthropic's Claude via API.

The new version of the library is a complete rewrite and hence is not backward compatible with the previous version. Previous methods of getResponse and getTextResponse have been removed.

## Features

- Support for all Claude models (Claude 3 Opus/Sonnet/Haiku, Claude 2.1)
- Streaming responses for real-time output
- Async request support using Promises
- Type-safe request/response handling
- Configurable request parameters (temperature, max tokens, etc)
- Comprehensive error handling and validation
- CLI tool for quick testing and API checks

## Requirements

- PHP 8.2 or higher
- Composer
- Valid Anthropic API key

## Installation

```bash
composer require dikki/claude-sdk
```

## Usage

```php
<?php

use Dikki\Claude\ClaudeBuilder;
use Dikki\Claude\Message\MessageBuilder;

// Initialize Claude client
$claude = (new ClaudeBuilder())
    ->withApiKey('your-api-key')
    ->build();
// Build message
$messages = (new MessageBuilder())
    ->assistant("You are a helpful AI assistant.")
    ->user("What is the meaning of life?")
    ->build();
// Send request and get response
$response = $claude->send($messages);
echo $response->getContent();
// Or use streaming for real-time responses
foreach ($claude->stream($messages) as $chunk) {
    echo $chunk->getContent();
}
```

## Limitations, for now

- WORK IN PROGRESS.
- No direct file upload support yet
- Need to add support for system messages
- Rate limits based on your Anthropic API plan
- We need to do more testing and add more examples before we can consider this production ready.

## License

MIT License - see LICENSE file for details.

For more detailed documentation and examples, please visit the the website [to be updated].
