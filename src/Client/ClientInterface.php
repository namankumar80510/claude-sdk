<?php

declare(strict_types=1);

namespace Dikki\Claude\Client;

use Dikki\Claude\Contracts\ResponseInterface;
use Dikki\Claude\Message\MessageCollection;
use GuzzleHttp\Promise\PromiseInterface;

interface ClientInterface
{
    public function sendRequest(MessageCollection $messages, array $options = []): ResponseInterface;
    
    public function streamRequest(MessageCollection $messages, array $options = []): \Generator;
    
    public function sendAsyncRequest(MessageCollection $messages, array $options = []): PromiseInterface;
} 