<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Dikki\Claude\ClaudeBuilder;
use Dikki\Claude\Enum\ModelEnum;
use Dikki\Claude\Message\MessageBuilder;

$claude = (new ClaudeBuilder())
    ->withApiKey(parse_ini_file(dirname(__DIR__) . '/.env')['CLAUDE_API_KEY'])
    ->withModel(ModelEnum::CLAUDE_2_1)
    ->withTimeout(60)
    ->withDebug(true)
    ->build();

$messages = (new MessageBuilder())
    ->assistant("You are a helpful AI assistant.")
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