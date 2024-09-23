#! /usr/bin/php env
<?php

/**
 * Run this file in CLI and see the response.
 * 
 * `php hello.php`
 * 
 * Ensure that you create a .env file in root and add your CLAUDE_API.
 */

use Dikki\Claude\Claude;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$claude = new Claude(parse_ini_file(dirname(__DIR__) . '/.env')['CLAUDE_API']);

var_dump($claude->getResponse("Define AI in one sentence."));