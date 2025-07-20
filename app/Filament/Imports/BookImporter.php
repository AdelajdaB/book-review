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

    public function afterSave(): void
    {
        GenerateBookDescriptionAndReviews::dispatch($this->record);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $successfulRows = $import->successful_rows;

        return "{$successfulRows} book" . \Illuminate\Support\Str::plural(' was', $successfulRows) . " successfully imported.";
    }
}
