<?php

declare(strict_types=1);

namespace Dikki\Claude;

use Dikki\Claude\Client\GuzzleClient;
use Dikki\Claude\Config\ClientConfig;
use Dikki\Claude\Config\ModelConfig;
use Dikki\Claude\Enum\ModelEnum;
use Dikki\Claude\Exception\ConfigurationException;
use Dikki\Claude\Validator\RequestValidator;

class ClaudeBuilder
{
    private string $apiKey;
    private ?string $model = null;
    private ?string $baseUrl = null;
    private ?string $apiVersion = null;
    private ?int $timeout = null;
    private ?int $connectTimeout = null;
    private bool $debug = false;
    private ?string $proxyUrl = null;
    private array $defaultHeaders = [];

    public function withApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    public function withModel(string|ModelEnum $model): self
    {
        $this->model = $model instanceof ModelEnum ? $model->value : $model;
        return $this;
    }

    public function withBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function withTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function withDebug(bool $debug = true): self
    {
        $this->debug = $debug;
        return $this;
    }

    public function build(): Claude
    {
        if (empty($this->apiKey)) {
            throw new ConfigurationException('API key is required');
        }

        $modelConfig = new ModelConfig(
            model: $this->model ?? ModelEnum::getDefault()->value
        );

        $clientConfig = new ClientConfig(
            apiKey: $this->apiKey,
            baseUrl: $this->baseUrl ?? 'https://api.anthropic.com/v1',
            apiVersion: $this->apiVersion ?? '2023-06-01',
            timeout: $this->timeout ?? 30,
            connectTimeout: $this->connectTimeout ?? 10,
            debug: $this->debug,
            proxyUrl: $this->proxyUrl,
            defaultHeaders: $this->defaultHeaders,
            modelConfig: $modelConfig
        );

        return new Claude(
            client: new GuzzleClient($clientConfig),
            config: $clientConfig,
            modelConfig: $modelConfig,
            validator: new RequestValidator()
        );
    }
} 