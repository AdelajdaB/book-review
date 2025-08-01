<?php

namespace App\Filament\Resources;

use App\Models\Book;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ImportAction;
use App\Filament\Imports\BookImporter;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\BookResource\Pages;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;


class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),

                TextColumn::make('title')
                    ->label('Title')
                    ->limit(20),

                TextColumn::make('status')
                    ->label('Status'),
            ])
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10])
            ->defaultSort('id', 'asc')
            ->filters([
                // Temporarily remove all filters
            ])
            ->actions([
                // DeleteAction::make(), // Temporarily disabled
            ])
            ->headerActions([
                ImportAction::make()
                ->importer(BookImporter::class),
            ])

            ->bulkActions([
                // DeleteBulkAction::make(), // Temporarily disabled
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
