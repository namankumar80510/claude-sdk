<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Dikki\Claude\ClaudeBuilder;
use Dikki\Claude\Enum\ModelEnum;
use Dikki\Claude\Message\MessageBuilder;

$claude = (new ClaudeBuilder())
    ->withApiKey($_ENV['CLAUDE_API_KEY'])
    ->withModel(ModelEnum::CLAUDE_3_SONNET)
    ->withTimeout(60)
    ->withDebug(true)
    ->build();

$messages = (new MessageBuilder())
    ->system("You are a helpful AI assistant.")
    ->user("What is the meaning of life?")
    ->build();

// Regular request
$response = $claude->send($messages);
echo $response->getContent() . "\n";

// Streaming request
foreach ($claude->stream($messages) as $chunk) {
    echo $chunk->getContent();
}

// Async request
$claude->sendAsync($messages)
    ->then(fn($response) => print $response->getContent())
    ->wait(); 