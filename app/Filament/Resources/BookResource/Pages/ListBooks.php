<?php

namespace App\Filament\Resources\BookResource\Pages;

use Filament\Actions;
use App\Jobs\ImportBooksJob;
use Filament\Actions\Action;
use Illuminate\Http\UploadedFile;
use App\Filament\Resources\BookResource;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Log;


class ListBooks extends ListRecords
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('Import Books')
                ->label('Import Books')
                ->form([
                    FileUpload::make('csv')
                        ->label('CSV File')
                        ->disk('local') // ← uses storage/app/private/
                        ->directory('imports') // ← stores in storage/app/private/imports
                        ->preserveFilenames()
                        ->acceptedFileTypes(['text/csv', '.csv'])
                        ->required()
                ])
                ->action(function (array $data) {
                    $csv = $data['csv'];

                    Log::info('CSV input data type: ' . gettype($csv));
                    Log::info('CSV input data value: ' . (is_object($csv) ? get_class($csv) : $csv));

                    if ($csv instanceof \Illuminate\Http\UploadedFile) {
                        $path = $csv->store('imports', 'local'); // e.g., 'imports/01ABC.csv'
                    } elseif (is_string($csv)) {
                        // 🛠 Fix: ensure it includes the 'imports/' folder
                        $path = str_starts_with($csv, 'imports/') ? $csv : 'imports/' . $csv;
                    } else {
                        throw new \Exception('Invalid CSV file input');
                    }

                    Log::info("Dispatching ImportBooksJob with path: $path");

                    ImportBooksJob::dispatch($path);
                }),

        ];
    }
}
