<?php

declare(strict_types=1);

namespace Dikki\Claude\Client;

use Dikki\Claude\Config\ClientConfig;
use Dikki\Claude\Contracts\ResponseInterface;
use Dikki\Claude\Exception\ApiException;
use Dikki\Claude\Message\MessageCollection;
use Dikki\Claude\Response\Response;
use Dikki\Claude\Response\StreamedResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class GuzzleClient implements ClientInterface
{
    private Client $client;
    private ResponseHandler $responseHandler;

    public function __construct(
        private readonly ClientConfig $config
    ) {
        $this->client = new Client([
            'base_uri' => $config->getBaseUrl(),
            'timeout' => $config->getTimeout(),
            'connect_timeout' => $config->getConnectTimeout(),
            'proxy' => $config->getProxyUrl(),
            'debug' => $config->isDebug(),
        ]);
        $this->responseHandler = new ResponseHandler();
    }

    public function sendRequest(MessageCollection $messages, array $options = []): ResponseInterface
    {
        try {
            $response = $this->client->post('/messages', [
                'headers' => $this->getHeaders(),
                'json' => $this->prepareRequestBody($messages, $options),
            ]);
            
            return $this->responseHandler->handle($response);
        } catch (GuzzleException $e) {
            throw new ApiException('Failed to send request: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function streamRequest(MessageCollection $messages, array $options = []): \Generator
    {
        $options['stream'] = true;
        
        try {
            $response = $this->client->post('/messages', [
                'headers' => $this->getHeaders(),
                'json' => $this->prepareRequestBody($messages, $options),
                'stream' => true,
            ]);
            
            return $this->responseHandler->handleStream($response);
        } catch (GuzzleException $e) {
            throw new ApiException('Failed to initiate stream: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function sendAsyncRequest(MessageCollection $messages, array $options = []): PromiseInterface
    {
        return $this->client->postAsync('/messages', [
            'headers' => $this->getHeaders(),
            'json' => $this->prepareRequestBody($messages, $options),
        ])->then(
            fn (PsrResponseInterface $response) => $this->responseHandler->handle($response),
            fn (\Throwable $e) => throw new ApiException('Async request failed: ' . $e->getMessage(), 0, $e)
        );
    }

    private function getHeaders(): array
    {
        return array_merge([
            'x-api-key' => $this->config->getApiKey(),
            'anthropic-version' => $this->config->getApiVersion(),
            'content-type' => 'application/json',
        ], $this->config->getDefaultHeaders());
    }

    private function prepareRequestBody(MessageCollection $messages, array $options): array
    {
        return array_filter([
            'messages' => $messages->toArray(),
            'model' => $options['model'] ?? null,
            'max_tokens' => $options['max_tokens'] ?? null,
            'temperature' => $options['temperature'] ?? null,
            'top_p' => $options['top_p'] ?? null,
            'top_k' => $options['top_k'] ?? null,
            'metadata' => $options['metadata'] ?? null,
            'stream' => $options['stream'] ?? null,
        ], fn ($value) => $value !== null);
    }
} 