<?php

declare(strict_types=1);

namespace Dikki\Claude;

use Dikki\Claude\Client\ClientInterface;
use Dikki\Claude\Config\ClientConfig;
use Dikki\Claude\Config\ModelConfig;
use Dikki\Claude\Contracts\ResponseInterface;
use Dikki\Claude\Message\MessageCollection;
use Dikki\Claude\Validator\RequestValidator;
use GuzzleHttp\Promise\PromiseInterface;

class Claude
{
    public function __construct(
        private readonly ClientInterface $client,
        private readonly ClientConfig $config,
        private readonly ModelConfig $modelConfig,
        private readonly RequestValidator $validator
    ) {}

    public function send(MessageCollection $messages, array $options = []): ResponseInterface
    {
        $this->validator->validate($messages, $options);
        return $this->client->sendRequest($messages, $options);
    }

    public function stream(MessageCollection $messages, array $options = []): \Generator
    {
        $this->validator->validate($messages, $options);
        return $this->client->streamRequest($messages, $options);
    }

    public function sendAsync(MessageCollection $messages, array $options = []): PromiseInterface
    {
        $this->validator->validate($messages, $options);
        return $this->client->sendAsyncRequest($messages, $options);
    }
}
