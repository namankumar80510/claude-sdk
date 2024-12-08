<?php

declare(strict_types=1);

namespace Dikki\Claude\Client;

use Dikki\Claude\Contracts\ResponseInterface;
use Dikki\Claude\Exception\ApiException;
use Dikki\Claude\Response\Response;
use Dikki\Claude\Response\StreamedResponse;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class ResponseHandler
{
    public function handle(PsrResponseInterface $response): ResponseInterface
    {
        $statusCode = $response->getStatusCode();
        
        if ($statusCode !== 200) {
            throw new ApiException(
                'API request failed with status code ' . $statusCode,
                $statusCode
            );
        }

        $data = json_decode($response->getBody()->getContents(), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException('Failed to decode API response');
        }

        return new Response($data);
    }

    public function handleStream(PsrResponseInterface $response): \Generator
    {
        $stream = $response->getBody();
        
        while (!$stream->eof()) {
            $line = $stream->read(1024);
            
            if (empty($line)) {
                continue;
            }

            $events = $this->parseStreamEvents($line);
            
            foreach ($events as $event) {
                if ($event === null) {
                    continue;
                }
                
                yield new StreamedResponse($event);
            }
        }
    }

    private function parseStreamEvents(string $chunk): array
    {
        $events = [];
        $lines = explode("\n", $chunk);
        
        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }

            if (str_starts_with($line, 'data: ')) {
                $data = substr($line, 6);
                if ($data === '[DONE]') {
                    break;
                }
                
                $decoded = json_decode($data, true);
                if ($decoded !== null) {
                    $events[] = $decoded;
                }
            }
        }
        
        return $events;
    }
} 