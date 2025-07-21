<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            $coverImagePath = $this->generateCoverImage($title, $author);
            
            return [
                'description' => trim($json[0]['summary_text']),
                'rating' => round(mt_rand(30, 50) / 10, 1), // random 3.0–5.0
                'cover_image' => $coverImagePath,
            ];
        }

        Log::error("Unexpected Hugging Face response: " . json_encode($json));
        return null;
    }

    private function generateCoverImage(string $title, string $author): ?string
    {
        try {
            // Try to get real book cover from Open Library API
            $realCover = $this->fetchRealBookCover($title, $author);
            if ($realCover) {
                return $realCover;
            }

            // If no real cover found, try to generate one with Hugging Face text-to-image
            $aiCover = $this->generateAICover($title, $author);
            if ($aiCover) {
                return $aiCover;
            }

            // Fallback to themed placeholder
            return $this->createThemedPlaceholder($title, $author);
            
        } catch (\Exception $e) {
            Log::error("Error generating cover image: " . $e->getMessage());
            return null;
        }
    }

    private function fetchRealBookCover(string $title, string $author): ?string
    {
        try {
            Log::info("Searching for real book cover: {$title} by {$author}");
            
            // Search Open Library for the book
            $searchQuery = urlencode("{$title} {$author}");
            $searchUrl = "https://openlibrary.org/search.json?title={$searchQuery}&limit=5";
            
            $response = Http::timeout(10)->get($searchUrl);
            
            if (!$response->successful()) {
                Log::warning("Open Library search failed for: {$title}");
                return null;
            }
            
            $searchData = $response->json();
            
            if (empty($searchData['docs'])) {
                Log::info("No books found in Open Library for: {$title}");
                return null;
            }
            
            // Look for a book with a cover
            foreach ($searchData['docs'] as $book) {
                if (isset($book['cover_i'])) {
                    $coverId = $book['cover_i'];
                    $coverUrl = "https://covers.openlibrary.org/b/id/{$coverId}-L.jpg";
                    
                    Log::info("Found cover ID {$coverId} for: {$title}");
                    
                    // Download the cover
                    $coverResponse = Http::timeout(15)->get($coverUrl);
                    
                    if ($coverResponse->successful()) {
                        $imageData = $coverResponse->body();
                        
                        if (!empty($imageData) && strlen($imageData) > 1000) { // Basic size check
                            Log::info("Successfully downloaded real cover for: {$title}");
                            return $this->storeImageData($imageData, $title, $author);
                        }
                    }
                }
            }
            
            Log::info("No suitable covers found in Open Library for: {$title}");
            return null;
            
        } catch (\Exception $e) {
            Log::warning("Error fetching real book cover: " . $e->getMessage());
            return null;
        }
    }

    private function generateAICover(string $title, string $author): ?string
    {
        try {
            // Try different Hugging Face text-to-image models
            $models = [
                'runwayml/stable-diffusion-v1-5',
                'stabilityai/stable-diffusion-2-1',
                'prompthero/openjourney-v4'
            ];

            $prompt = $this->generateBookCoverPrompt($title, $author);
            
            foreach ($models as $model) {
                try {
                    Log::info("Trying AI cover generation with {$model} for: {$title}");
                    
                    $response = Http::withToken(config('services.huggingface.api_key'))
                        ->timeout(120)
                        ->post("https://api-inference.huggingface.co/models/{$model}", [
                            'inputs' => $prompt,
                            'options' => ['wait_for_model' => true]
                        ]);

                    if ($response->successful()) {
                        $imageData = $response->body();
                        
                        if (!empty($imageData) && !str_starts_with($imageData, '{"error"')) {
                            Log::info("Successfully generated AI cover with {$model} for: {$title}");
                            return $this->storeImageData($imageData, $title, $author);
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("Model {$model} failed: " . $e->getMessage());
                    continue;
                }
            }
            
            Log::info("All AI models failed for: {$title}");
            return null;
            
        } catch (\Exception $e) {
            Log::warning("Error generating AI cover: " . $e->getMessage());
            return null;
        }
    }

    private function generateBookCoverPrompt(string $title, string $author): string
    {
        // Create a more specific prompt for book covers
        $genres = ['mystery', 'romance', 'fantasy', 'sci-fi', 'literary fiction', 'thriller'];
        $styles = ['minimalist', 'vintage', 'modern', 'artistic', 'elegant'];
        
        $genre = $genres[array_rand($genres)];
        $style = $styles[array_rand($styles)];
        
        return "Professional book cover design, {$style} style, {$genre} theme, elegant typography, no text overlay, symbolic imagery, high quality, digital art, book cover layout, publisher quality";
    }

    private function createThemedPlaceholder(string $title, string $author): ?string
    {
        try {
            // Create a better placeholder using a color scheme based on the book
            $colors = [
                ['bg' => '2c3e50', 'text' => 'ecf0f1'], // Dark blue-gray
                ['bg' => '8e44ad', 'text' => 'ffffff'], // Purple
                ['bg' => '27ae60', 'text' => 'ffffff'], // Green
                ['bg' => 'e74c3c', 'text' => 'ffffff'], // Red
                ['bg' => '3498db', 'text' => 'ffffff'], // Blue
                ['bg' => 'f39c12', 'text' => 'ffffff'], // Orange
            ];
            
            $colorIndex = abs(crc32($title . $author)) % count($colors);
            $color = $colors[$colorIndex];
            
            // Use a placeholder service that supports custom colors and text
            $imageUrl = "https://dummyimage.com/400x600/{$color['bg']}/{$color['text']}.png&text=" . 
                       urlencode(wordwrap($title, 15, "\n") . "\n\nby\n" . $author);
            
            Log::info("Creating themed placeholder for: {$title}");
            
            $response = Http::timeout(15)->get($imageUrl);
            
            if ($response->successful()) {
                $imageData = $response->body();
                if (!empty($imageData)) {
                    Log::info("Successfully created themed placeholder for: {$title}");
                    return $this->storeImageData($imageData, $title, $author);
                }
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::warning("Error creating themed placeholder: " . $e->getMessage());
            return null;
        }
    }

    private function storeImageData(string $imageData, string $title, string $author): ?string
    {
        try {
            // Create a filename
            $filename = 'covers/' . date('Y/m/') . Str::slug($title . '-' . $author) . '-' . time() . '.png';
            
            // Store the binary image data
            Storage::disk('public')->put($filename, $imageData);
            
            return $filename;

        } catch (\Exception $e) {
            Log::error("Error storing cover image: " . $e->getMessage());
            return null;
        }
    }

}
