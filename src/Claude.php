<?php

declare(strict_types=1);

namespace Dikki\Claude;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Claude
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly string $apiKey,
        private readonly string $model = 'claude-instant-1.2',
        private readonly string $modelVersion = '2023-06-01'
    ) {
        $this->client = new Client();
    }

    public function getEndpoint(): string
    {
        return "https://api.anthropic.com/v1/messages";
    }

    /**
     * @param  string       $prompt
     * @param  array<mixed> $messages
     * @param  string|null  $model
     * @param  int          $maxTokens
     * @param  string       $method
     * @return array<mixed>
     * @throws GuzzleException
     */
    public function getResponse(
        string $prompt,
        array $messages = [],
        string $model = null,
        int $maxTokens = 4000,
        string $method = 'POST'
    ): array {

        if (empty($messages)) {
            $messages = [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ];
        }
        if (empty($model)) {
            $model = $this->model;
        }

        $response = $this->client->request(
            $method,
            $this->getEndpoint(),
            [
                'headers' => [
                    'x-api-key' => $this->apiKey,
                    'content-type' => 'application/json',
                    'anthropic-version' => $this->modelVersion
                ],
                'json' => [
                    'model' => $model,
                    'max_tokens' => $maxTokens,
                    'messages' => $messages
                ],
                'verify' => false,
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param  string       $prompt
     * @param  array<mixed> $messages
     * @param  string|null  $model
     * @param  int          $maxTokens
     * @param  string       $method
     * @return string
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
}
