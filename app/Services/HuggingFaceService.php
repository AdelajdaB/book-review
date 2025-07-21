<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HuggingFaceService
{
    protected string $model = 'facebook/bart-large-cnn';

    private function generateRandomBookInput(string $title, string $author): string
    {
        $templates = [
            "{$title} by {$author} is an extraordinary literary work that captivates readers with its compelling narrative and rich character development.",
            "Written by {$author}, {$title} presents a fascinating exploration of human nature through its intricate plot and memorable characters.",
            "{$title} is {$author}'s masterful creation that weaves together themes of love, loss, and redemption in a beautifully crafted story.",
            "In {$title}, author {$author} delivers a powerful and thought-provoking tale that challenges readers' perspectives and emotions.",
            "{$author}'s {$title} is a remarkable novel that combines vivid storytelling with profound insights into the human condition.",
            "The book {$title} by {$author} offers readers an immersive experience filled with unexpected twists and emotional depth.",
            "{$title}, penned by {$author}, is a gripping narrative that explores complex relationships and personal growth.",
            "Author {$author} presents {$title} as a compelling story that resonates with universal themes and unforgettable moments.",
            "{$title} stands as {$author}'s brilliant contribution to literature, featuring engaging characters and a captivating storyline.",
            "In this remarkable work, {$author} crafts {$title} as an emotionally charged journey that leaves a lasting impact on readers."
        ];

        return $templates[array_rand($templates)];
    }

public function generateBookData(string $title, string $author): ?array
    {
        $input = $this->generateRandomBookInput($title, $author);

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
