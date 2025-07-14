<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class BookStatusWidget extends BaseWidget
{
    protected function getHeading(): string
    {
        return '📚 Books';
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Books Added', Book::where('status', 'added')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Processing', Book::where('status', 'processing')->count())
                ->icon('heroicon-o-arrow-path-rounded-square')
                ->color('warning'),

            Stat::make('Failed', Book::where('status', 'failed')->count())
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger'),
        ];
    }
}