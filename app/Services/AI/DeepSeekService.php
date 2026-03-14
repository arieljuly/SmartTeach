<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSeekService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://api.deepseek.com/v1';

    public function __construct()
    {
        $this->apiKey = env('DEEPSEEK_API_KEY');
        $this->model = env('DEEPSEEK_MODEL', 'deepseek-chat');
        
        if (empty($this->apiKey)) {
            throw new \Exception('DeepSeek API key is not configured');
        }
    }

    /**
     * Generate content using DeepSeek
     */
    public function generate(string $prompt, float $temperature = 0.7, int $maxTokens = 4000)
    {
        try {
            Log::info('Sending request to DeepSeek', [
                'model' => $this->model,
                'prompt_length' => strlen($prompt)
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
                            'content' => 'You are an expert curriculum developer and educational content creator. Always respond with valid JSON only, no other text.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => $temperature,
                    'max_tokens' => $maxTokens,
                    'response_format' => ['type' => 'json_object'] // Force JSON response
                ]);

            if ($response->failed()) {
                Log::error('DeepSeek API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                $error = $response->json();
                throw new \Exception($error['error']['message'] ?? 'DeepSeek request failed');
            }

            $data = $response->json();
            
            if (!isset($data['choices'][0]['message']['content'])) {
                Log::error('Unexpected DeepSeek response format', ['data' => $data]);
                throw new \Exception('Unexpected response format from DeepSeek');
            }

            return $data['choices'][0]['message']['content'];

        } catch (\Exception $e) {
            Log::error('DeepSeek Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if API key is valid
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/models');
            
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}