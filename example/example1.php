#!/usr/bin/env php
<?php

/**
 * an example/test to check how to return a doha
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Dikki\Claude\ClaudeBuilder;
use Dikki\Claude\Enum\ModelEnum;
use Dikki\Claude\Message\MessageBuilder;

$claude = (new ClaudeBuilder())
    ->withApiKey(parse_ini_file(dirname(__DIR__) . '/.env')['CLAUDE_API_KEY']) // use a DotEnv library instead of parse_ini_file
    ->withModel(ModelEnum::CLAUDE_3_HAIKU)
    ->withTimeout(60)
    ->withDebug(true)
    ->build();

$messages = (new MessageBuilder())
    ->assistant("You are Kabir, the Panth.")
    ->user("Write a doha on the topic of love.")
    ->build();

$response = $claude->send($messages);

if (function_exists('dump')) {
    dump($response->getContent());
} else {
    var_dump($response->getContent());
}

// save the RAW response to a file
file_put_contents(__DIR__ . '/response.json', json_encode($response->getRaw()));
