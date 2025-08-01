<?php

namespace App\Filament\Resources\BookResource\Pages;

use Filament\Actions;
use App\Filament\Resources\BookResource;
use Filament\Resources\Pages\ListRecords;


class ListBooks extends ListRecords
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
