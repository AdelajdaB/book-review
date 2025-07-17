<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HuggingFaceService
{
    protected string $model = 'facebook/bart-large-cnn';

public function generateBookData(string $title, string $author): ?array
    {
        $input = "{$title} is a book written by {$author}. It is a thrilling and imaginative novel that explores deep themes of courage and transformation."; // fake short blurb

        $url = "https://api-inference.huggingface.co/models/facebook/bart-large-cnn";

        $response = Http::withToken(config('services.huggingface.api_key'))
            ->timeout(60)
            ->post($url, [
                'inputs' => $input,
            ]);

        if (!$response->successful()) {
            Log::error("Hugging Face API error: " . $response->body());
            return null;
        }

        $json = $response->json();

        if (!empty($json[0]['summary_text'])) {
            return [
                'description' => trim($json[0]['summary_text']),
                'rating' => round(mt_rand(30, 50) / 10, 1), // random 3.0–5.0
            ];
        }

        Log::error("Unexpected Hugging Face response: " . json_encode($json));
        return null;
    }

}
