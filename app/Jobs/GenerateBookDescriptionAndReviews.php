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
        Log::info("Starting AI generation for book: {$this->book->title} (ID: {$this->book->id})");
        
        try {
            $data = $ai->generateBookData($this->book->title, $this->book->author);

            if (!$data) {
                throw new \Exception("Failed to generate AI data - service returned null.");
            }

            Log::info("AI data generated successfully for book: {$this->book->title}", [
                'description_length' => strlen($data['description']),
                'rating' => $data['rating']
            ]);

            $this->book->update([
                'description' => $data['description'],
                'rating' => $data['rating'],
                'status' => 'added',
            ]);

            Log::info("Book updated successfully: {$this->book->title} - Status: added");

        } catch (\Throwable $e) {
            Log::error("AI generation failed for book {$this->book->title} (ID: {$this->book->id}): " . $e->getMessage(), [
                'exception' => $e->getTraceAsString()
            ]);
            
            $this->book->update(['status' => 'failed']);
            
            // Re-throw the exception so the job is marked as failed
            throw $e;
        }
    }
}

