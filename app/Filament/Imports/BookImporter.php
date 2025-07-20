<?php

namespace App\Filament\Imports;

use App\Models\Book;
use Illuminate\Support\Str;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;
use App\Jobs\GenerateBookDescriptionAndReviews;

class BookImporter extends Importer
{
    protected static ?string $model = Book::class;
    protected bool $shouldQueue = true;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('author')
                ->rules(['required', 'string', 'max:255']),
        ];
    }

    public function resolveRecord(): ?Book
    {
        return Book::firstOrNew([
            'title' => $this->data['title'],
            'author' => $this->data['author'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $successfulRows = $import->successful_rows;

        // Dispatch AI generation jobs for all successfully imported books
        static::dispatchAiGenerationJobs($import);

        return "{$successfulRows} book" . \Illuminate\Support\Str::plural(' was', $successfulRows) . " successfully imported.";
    }

    protected static function dispatchAiGenerationJobs(Import $import): void
    {
        // Get all books that were created during this import
        // We'll use the import's created_at timestamp to find recently created books
        $recentBooks = Book::where('created_at', '>=', $import->created_at)
            ->whereNull('description') // Only books that don't have AI-generated content yet
            ->get();

        foreach ($recentBooks as $book) {
            GenerateBookDescriptionAndReviews::dispatch($book);
        }
    }
}
