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

    /**
     * For some reason, the base url is not working when using the /v1/ endpoint;
     * so had to manually add it to each request.
     */
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
            $response = $this->client->post('/v1/messages', [
                'headers' => $this->getHeaders(),
                'json' => array_merge(
                    ['model' => $this->config->getModelConfig()->getModel()],
                    $this->prepareRequestBody($messages, $options)
                ),
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
            $response = $this->client->post('/v1/messages', [
                'headers' => $this->getHeaders(),
                'json' => array_merge(
                    ['model' => $this->config->getModelConfig()->getModel()],
                    $this->prepareRequestBody($messages, $options)
                ),
                'stream' => true,
            ]);
            
            return $this->responseHandler->handleStream($response);
        } catch (GuzzleException $e) {
            throw new ApiException('Failed to initiate stream: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function sendAsyncRequest(MessageCollection $messages, array $options = []): PromiseInterface
    {
        return $this->client->postAsync('/v1/messages', [
            'headers' => $this->getHeaders(),
            'json' => array_merge(
                ['model' => $this->config->getModelConfig()->getModel()],
                $this->prepareRequestBody($messages, $options)
            ),
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

    private function prepareRequestBody(MessageCollection $messages, array $options = []): array
    {
        return array_filter([
            'messages' => $messages->toArray(),
            'max_tokens' => $options['max_tokens'] ?? $this->config->getModelConfig()->getMaxTokens(),
            'temperature' => $options['temperature'] ?? $this->config->getModelConfig()->getTemperature(),
            'top_p' => $options['top_p'] ?? $this->config->getModelConfig()->getTopP(),
            'top_k' => $options['top_k'] ?? $this->config->getModelConfig()->getTopK(),
            'stop_sequences' => $options['stop_sequences'] ?? $this->config->getModelConfig()->getStopSequences(),
            'stream' => $options['stream'] ?? false,
        ]);
    }
} 