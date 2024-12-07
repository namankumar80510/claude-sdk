<?php

declare(strict_types=1);

namespace Dikki\Claude\Config;

class ClientConfig
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl = 'https://api.anthropic.com/v1',
        private readonly string $apiVersion = '2024-02-29',
        private readonly int $timeout = 30,
        private readonly int $connectTimeout = 10,
        private readonly bool $debug = false,
        private readonly ?string $proxyUrl = null,
        private readonly array $defaultHeaders = []
    ) {}

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getConnectTimeout(): int
    {
        return $this->connectTimeout;
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }

    public function getProxyUrl(): ?string
    {
        return $this->proxyUrl;
    }

    public function getDefaultHeaders(): array
    {
        return $this->defaultHeaders;
    }
} 