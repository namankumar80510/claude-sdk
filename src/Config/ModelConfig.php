<?php

declare(strict_types=1);

namespace Dikki\Claude\Config;

use Dikki\Claude\Enum\ModelEnum;
use Dikki\Claude\Exception\ConfigurationException;

class ModelConfig
{
    private ?string $model;
    private float $temperature;
    private ?float $topP;
    private ?int $topK;
    private ?array $stopSequences;
    private ?int $maxTokens;

    public function __construct(
        ?string $model = null,
        float $temperature = 0.7,
        float $topP = null,
        int $topK = null,
        array $stopSequences = null,
        int $maxTokens = 4096
    ) {
        $this->setModel($model ?? ModelEnum::getDefault()->value);
        $this->setTemperature($temperature);
        $this->topP = $topP;
        $this->topK = $topK;
        $this->stopSequences = $stopSequences;
        $this->maxTokens = $maxTokens;
    }

    private function setModel(string $model): void
    {
        if (!ModelEnum::isValid($model)) {
            throw new ConfigurationException("Invalid model: {$model}");
        }
        $this->model = $model;
    }

    private function setTemperature(float $temperature): void
    {
        if ($temperature < 0 || $temperature > 1) {
            throw new ConfigurationException('Temperature must be between 0 and 1');
        }
        $this->temperature = $temperature;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function getTopP(): ?float
    {
        return $this->topP;
    }

    public function getTopK(): ?int
    {
        return $this->topK;
    }

    public function getStopSequences(): ?array
    {
        return $this->stopSequences;
    }

    public function getMaxTokens(): int
    {
        return $this->maxTokens;
    }

    public function toArray(): array
    {
        return array_filter([
            'model' => $this->model,
            'temperature' => $this->temperature,
            'top_p' => $this->topP,
            'top_k' => $this->topK,
            'stop_sequences' => $this->stopSequences,
            'max_tokens' => $this->maxTokens,
        ], fn($value) => $value !== null);
    }
} 
