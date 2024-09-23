<?php

declare(strict_types=1);

namespace Dikki\Claude;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Claude
 *
 * This class provides methods to interact with the Claude API.
 *
 * @package Dikki\Claude
 */
class Claude
{
    private Client $client;


    /**
     * Claude constructor.
     *
     * @param string $apiKey The API key for authentication.
     * @param string $model The model to use for the API requests.
     * @param string $modelVersion The version of the model to use.
     * @throws Exception
     */
    public function __construct(
        private readonly string $apiKey,
        private readonly string $model = 'claude-2.1',
        private readonly string $modelVersion = '2023-06-01'
    ) {
        $this->client = new Client();
    }


    /**
     * Get the API endpoint URL.
     *
     * @return string The API endpoint URL.
     */
    public function getEndpoint(): string
    {
        return "https://api.anthropic.com/v1/messages";
    }


    /**
     * Get the response from the Claude API.
     *
     * @param string $prompt The prompt to send to the API.
     * @param array<mixed> $messages The messages to send to the API.
     * @param string|null $model The model to use for the API request.
     * @param int $maxTokens The maximum number of tokens to generate.
     * @param string $method The HTTP method to use for the request.
     * @return array<mixed> The response from the API.
     * @throws GuzzleException
     */
    public function getResponse(
        string $prompt,
        array $messages = [],
        string $model = null,
        int $maxTokens = 4000,
        string $method = 'POST'
    ): array {
        $messages = $this->prepareMessages($prompt, $messages);
        $model = $model ?? $this->model;

        $response = $this->client->request(
            $method,
            $this->getEndpoint(),
            [
                'headers' => $this->getHeaders(),
                'json' => $this->getRequestBody($model, $maxTokens, $messages),
                'verify' => false,
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * Get the text response from the Claude API.
     *
     * @param string $prompt The prompt to send to the API.
     * @param array<mixed> $messages The messages to send to the API.
     * @param string|null $model The model to use for the API request.
     * @param int $maxTokens The maximum number of tokens to generate.
     * @param string $method The HTTP method to use for the request.
     * @return string The text response from the API.
     * @throws GuzzleException
     */
    public function getTextResponse(
        string $prompt,
        array $messages = [],
        string $model = null,
        int $maxTokens = 4000,
        string $method = 'POST'
    ): string {
        return $this->getResponse($prompt, $messages, $model, $maxTokens, $method)['content'][0]['text'];
    }


    /**
     * Prepare the messages for the API request.
     *
     * @param string $prompt The prompt to send to the API.
     * @param array<mixed> $messages The messages to send to the API.
     * @return array<mixed> The prepared messages.
     */
    private function prepareMessages(string $prompt, array $messages): array
    {
        if (empty($messages)) {
            $messages = [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ];
        }
        return $messages;
    }


    /**
     * Get the headers for the API request.
     *
     * @return array<string, string> The headers for the API request.
     */
    private function getHeaders(): array
    {
        return [
            'x-api-key' => $this->apiKey,
            'content-type' => 'application/json',
            'anthropic-version' => $this->modelVersion
        ];
    }


    /**
     * Get the request body for the API request.
     *
     * @param string $model The model to use for the API request.
     * @param int $maxTokens The maximum number of tokens to generate.
     * @param array<mixed> $messages The messages to send to the API.
     * @return array<string, mixed> The request body for the API request.
     */
    private function getRequestBody(string $model, int $maxTokens, array $messages): array
    {
        return [
            'model' => $model,
            'max_tokens' => $maxTokens,
            'messages' => $messages
        ];
    }


}
