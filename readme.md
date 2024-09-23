# Claude API PHP SDK

This is a simple but useful PHP Class that you can use to communicate with Anthropic's Claude via API.

## Installation

```bash
composer require dikki/claude-sdk
```

## Usage

### Creating an Instance

First, create an instance of the `Claude` class. Pass the API key as the first parameter. You can pass the model name as the second parameter or keep it empty to use the default model (`claude-2.1`).

```php
$claude = new \Dikki\Claude\Claude($apiKey, 'claude-2.1');
```

### Methods

#### getResponse

To get the whole response from Claude, use the `getResponse()` method. It accepts the following parameters:

- **string $prompt**: The prompt to send to the API.
- **array $messages**: Optional. The messages to send to the API.
- **string|null $model**: Optional. The model to use for the API request.
- **int $maxTokens**: Optional. The maximum number of tokens to generate (default is 4000).
- **string $method**: Optional. The HTTP method to use for the request (default is 'POST').

Returns an array containing the full response, including text, model used, etc.

```php
$response = $claude->getResponse("Write an essay on AI.");
```

#### getTextResponse

To get only the string response, use the `getTextResponse()` method. It accepts the same parameters as `getResponse()`.

Returns a string response.

```php
$response = $claude->getTextResponse("Write an essay on AI.");
```

### Example Usage

```php
// Create an instance of Claude
$claude = new \Dikki\Claude\Claude($apiKey, 'claude-2.1');

// Get full response
$response = $claude->getResponse("Write an essay on AI.");

// Get only text response
$textResponse = $claude->getTextResponse("Write an essay on AI.");
```

### Class Overview

- **Class**: `Claude`
- **Namespace**: `Dikki\Claude`
- **Constructor Parameters**:
  - `string $apiKey`: The API key for authentication.
  - `string $model`: The model to use for the API requests (default is 'claude-2.1').
  - `string $modelVersion`: The version of the model to use (default is '2023-06-01').

### Additional Information

- **getEndpoint()**: Returns the API endpoint URL.
- **prepareMessages()**: Prepares the messages for the API request.
- **getHeaders()**: Returns the headers for the API request.
- **getRequestBody()**: Returns the request body for the API request.

