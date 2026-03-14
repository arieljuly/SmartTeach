<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://api.groq.com/openai/v1';

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
        $this->model = config('services.groq.model', 'llama3-8b-8192');
        
        if (empty($this->apiKey)) {
            throw new \Exception('Groq API key is not configured. Please set GROQ_API_KEY in .env file.');
        }
    }

    /**
     * Generate content using Groq
     */
    public function generate(string $prompt, float $temperature = 0.7, int $maxTokens = 4000)
    {
        try {
            Log::info('Sending request to Groq', [
                'model' => $this->model,
                'prompt_length' => strlen($prompt),
                'temperature' => $temperature,
                'max_tokens' => $maxTokens
            ]);

            $response = Http::timeout(120) // 2 minutes timeout
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl . '/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert curriculum developer and educational content creator. Always respond with valid JSON only. Never include any explanatory text outside the JSON.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => $temperature,
                    'max_tokens' => $maxTokens,
                    'top_p' => 0.9,
                    'response_format' => ['type' => 'json_object'] // Force JSON response
                ]);

            if ($response->failed()) {
                Log::error('Groq API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                $error = $response->json();
                $errorMessage = $error['error']['message'] ?? 'Groq request failed';
                throw new \Exception('Groq API Error: ' . $errorMessage);
            }

            $data = $response->json();
            
            if (!isset($data['choices'][0]['message']['content'])) {
                Log::error('Unexpected Groq response format', ['data' => $data]);
                throw new \Exception('Unexpected response format from Groq');
            }

            $content = $data['choices'][0]['message']['content'];
            
            // Log success (but not the full content to avoid huge logs)
            Log::info('Groq request successful', [
                'model' => $this->model,
                'response_length' => strlen($content),
                'usage' => $data['usage'] ?? null
            ]);

            return $content;

        } catch (\Exception $e) {
            Log::error('Groq Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if API key is valid and service is available
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ])
                ->get($this->baseUrl . '/models');
            
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get available models
     */
    public function getAvailableModels(): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/models');
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? [];
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch Groq models: ' . $e->getMessage());
        }
        
        return [];
    }
}