# Claude API PHP SDK

## Installation

```bash
composer require dikki/claude-sdk
```

## Usage

First create an instance of Claude class. Pass the api key as first parameter.

You can pass the model name as second parameter or keep it empty to use default model (claude-instant-1.2).

```php

$claude = new \Dikki\Claude\Claude($apiKey, 'claude-2.1');

```

Next, pass a prompt to getResponse() method to get the whole response from claude, or to getTextResponse() to get only
string response.

```php
$response = $claude->getResponse("Write an essay on AI."); // returns an array as response containing, text, model used, etc.

$response = $claude->getTextResponse("Write an essay on AI."); // returns only string response
```