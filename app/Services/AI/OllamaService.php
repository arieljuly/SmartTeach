<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    protected string $baseUrl;
    protected string $model;

    public function __construct()
    {
        $this->baseUrl = env('OLLAMA_URL', 'http://localhost:11434');
        $this->model = env('OLLAMA_MODEL', 'llamamistral');
    }

    public function generate(string $prompt, float $temperature = 0.7, int $maxTokens = 2000)
    {
        try {
            $response = Http::timeout(120) // 2 minutes timeout for longer generations
                ->post($this->baseUrl . '/api/chat', [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are an expert curriculum developer and educational content creator. Always respond in valid JSON format.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'stream' => false,
                    'options' => [
                        'temperature' => $temperature,
                        'num_predict' => $maxTokens,
                    ]
                ]);

            if ($response->failed()) {
                Log::error('Ollama API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            $data = $response->json();
            return $data['message']['content'] ?? null;

        } catch (\Exception $e) {
            Log::error('Ollama Service Error: ' . $e->getMessage());
            return null;
        }
    }

    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)->get($this->baseUrl . '/api/tags');
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}