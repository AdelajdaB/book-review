<?php

namespace App\Jobs;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\HuggingFaceService;

class GenerateBookDescriptionAndReviews implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Book $book) {}

    public function handle(HuggingFaceService $ai): void
    {
        try {
            $data = $ai->generateBookData($this->book->title, $this->book->author);

            if (!$data) {
                throw new \Exception("Failed to generate AI data.");
            }

            $this->book->update([
                'description' => $data['description'],
                'rating' => $data['rating'],
                'status' => 'added',
            ]);

            Log::info("AI data generated via Hugging Face for book: {$this->book->title}");

        } catch (\Throwable $e) {
            $this->book->update(['status' => 'failed']);
            Log::error("AI generation failed for book {$this->book->title}: " . $e->getMessage());
        }
    }
}

