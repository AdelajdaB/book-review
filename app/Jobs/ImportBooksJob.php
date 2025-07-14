<?php

namespace App\Jobs;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\GenerateBookDescriptionAndReviews;
use Illuminate\Support\Facades\Log;

class ImportBooksJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $path) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting import from CSV: {$this->path}");

        if (!Storage::exists($this->path)) {
            Log::error("File does not exist at path: {$this->path}");
        return;
    }

        $csv = Storage::disk('local')->get($this->path);
        Log::info("CSV content first 100 chars: " . substr($csv, 0, 100));

        $lines = preg_split('/\r\n|\n|\r/', $csv); // no filter yet
        $rawHeaderLine = array_shift($lines);

        // Remove UTF-8 BOM if present
        $rawHeaderLine = preg_replace('/^\xEF\xBB\xBF/', '', $rawHeaderLine);

        $header = array_map(
            fn($h) => strtolower(trim($h, "\"' \t\n\r\0\x0B")),
            str_getcsv($rawHeaderLine)
        );

        // Now clean the remaining lines
        $lines = array_filter($lines, fn($line) => trim($line) !== '');

        Log::info('Parsed CSV header: ' . json_encode($header));

        $count = 0;

        foreach ($lines as $row) {
            $rowData = array_map(fn($v) => trim($v, "\"' \t\n\r\0\x0B"), str_getcsv($row));
            $data = array_combine($header, $rowData);

            if (!$data || empty($data['title']) || empty($data['author'])) {
                Log::warning("Skipped row due to missing title or author: " . json_encode($row));
                continue;
            }

            $existingBook = Book::where('title', $data['title'])
                ->where('author', $data['author'])
                ->first();

            if ($existingBook) {
                Log::info("Skipped duplicate book: {$data['title']} by {$data['author']}");
                continue;
            }

            // Create new book
            $book = Book::create([
                'title' => $data['title'],
                'author' => $data['author'],
                'status' => 'added'
            ]);

            Log::info("Imported book: {$book->title} by {$book->author}");

            GenerateBookDescriptionAndReviews::dispatch($book);

            $count++;
        }

        Log::info("Finished import from CSV: {$this->path}. Total books imported: {$count}");

    }
}
