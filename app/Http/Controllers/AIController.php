<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AI\OpenAIService;

class AIController extends Controller
{
    protected $openAI;

    public function __construct(OpenAIService $openAI)
    {
        $this->openAI = $openAI;
    }

    public function ask(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string'
        ]);

        $response = $this->openAI->generate($request->prompt);

        return response()->json($response);
    }
}